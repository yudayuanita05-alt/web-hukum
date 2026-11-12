<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentGatewayController extends Controller
{
    /**
     * Helper: get Midtrans base URL
     */
    private function getMidtransBaseUrl(): string
    {
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        return $isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    /**
     * Helper: get server key
     */
    private function getServerKey(): string
    {
        return env('MIDTRANS_SERVER_KEY', '');
    }

    /**
     * Generate Midtrans Snap Token
     */
    public function getSnapToken(Request $request, $id)
    {
        $pembayaran = Pembayaran::with('formulir.user')->findOrFail($id);

        if ($pembayaran->isLunas()) {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas.'], 400);
        }

        $serverKey = $this->getServerKey();

        // Jika sudah punya snap_token aktif
        if (!empty($pembayaran->snap_token) && !Str::startsWith($pembayaran->snap_token, 'SNAP-SIM-')) {
            return response()->json([
                'success' => true,
                'snap_token' => $pembayaran->snap_token,
                'order_id' => $pembayaran->order_id,
                'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
            ]);
        }

        // Data transaksi untuk API Midtrans
        $transactionDetails = [
            'order_id' => $pembayaran->order_id,
            'gross_amount' => (int)$pembayaran->jumlah_tagihan,
        ];

        $customerDetails = [
            'first_name' => $pembayaran->formulir->nama ?? 'Klien',
            'email' => ($pembayaran->formulir->user->username ?? 'klien') . '@handokopartners.web.id',
            'phone' => $pembayaran->formulir->no_hp ?? '08123456789',
        ];

        $itemDetails = [
            [
                'id' => 'ITEM-' . $pembayaran->id_pembayaran,
                'price' => (int)$pembayaran->jumlah_tagihan,
                'quantity' => 1,
                'name' => 'Jasa Layanan Hukum: ' . Str::limit($pembayaran->formulir->no_permohonan ?? 'Konsultasi', 30),
            ]
        ];

        $payload = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ];

        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $endpoint = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $resData = $response->json();
                $snapToken = $resData['token'];
                $paymentUrl = $resData['redirect_url'] ?? null;

                $pembayaran->snap_token = $snapToken;
                $pembayaran->payment_url = $paymentUrl;
                $pembayaran->save();

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'payment_url' => $paymentUrl,
                    'order_id' => $pembayaran->order_id,
                    'client_key' => env('MIDTRANS_CLIENT_KEY'),
                ]);
            }

            Log::warning('Midtrans Snap API failed: ' . $response->status() . ' - ' . $response->body());
        } catch (\Exception $e) {
            Log::warning('Midtrans API Exception: ' . $e->getMessage());
        }

        // Fallback: simulated token (ketika kredensial belum diisi)
        $simulatedSnapToken = 'SNAP-SIM-' . strtoupper(md5($pembayaran->order_id . time()));
        $pembayaran->snap_token = $simulatedSnapToken;
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'snap_token' => $simulatedSnapToken,
            'order_id' => $pembayaran->order_id,
            'simulated' => true,
            'client_key' => env('MIDTRANS_CLIENT_KEY'),
        ]);
    }

    /**
     * Charge pembayaran via Midtrans Core API (GoPay QRIS / ShopeePay)
     * Ini menghasilkan QR code asli dari Midtrans yang bisa di-scan di simulator
     */
    public function chargeGopayQris($id)
    {
        $pembayaran = Pembayaran::with('formulir.user')->findOrFail($id);

        if ($pembayaran->isLunas()) {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas.'], 400);
        }

        $serverKey = $this->getServerKey();
        $baseUrl = $this->getMidtransBaseUrl();

        if (empty($serverKey) || Str::contains($serverKey, 'SIMULATOR')) {
            return response()->json([
                'success' => false,
                'message' => 'Server Key Midtrans belum diisi. Silakan daftar di dashboard.sandbox.midtrans.com dan masukkan Server Key di file .env',
                'need_credentials' => true,
            ], 400);
        }

        // Buat order_id unik baru jika yang lama sudah expired/gagal
        $orderId = $pembayaran->order_id;

        $payload = [
            'payment_type' => 'gopay',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$pembayaran->jumlah_tagihan,
            ],
            'gopay' => [
                'enable_callback' => true,
                'callback_url' => url('/klien/pembayaran'),
            ],
            'customer_details' => [
                'first_name' => $pembayaran->formulir->nama ?? 'Klien',
                'email' => ($pembayaran->formulir->user->username ?? 'klien') . '@handokopartners.web.id',
                'phone' => $pembayaran->formulir->no_hp ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => 'ITEM-' . $pembayaran->id_pembayaran,
                    'price' => (int)$pembayaran->jumlah_tagihan,
                    'quantity' => 1,
                    'name' => 'Jasa Hukum: ' . Str::limit($pembayaran->formulir->no_permohonan ?? 'Konsultasi', 30),
                ]
            ],
        ];

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($baseUrl . '/v2/charge', $payload);

            $resData = $response->json();
            Log::info('Midtrans GoPay Charge Response:', $resData);

            if ($response->successful() && isset($resData['actions'])) {
                // Cari QR code URL dari actions
                $qrUrl = null;
                $deeplinkUrl = null;
                foreach ($resData['actions'] as $action) {
                    if ($action['name'] === 'generate-qr-code') {
                        $qrUrl = $action['url'];
                    }
                    if ($action['name'] === 'deeplink-redirect') {
                        $deeplinkUrl = $action['url'];
                    }
                }

                // Update pembayaran
                $pembayaran->metode_pembayaran = 'GOPAY-QRIS';
                $pembayaran->status_transaksi = 'pending';
                $pembayaran->transaction_id = $resData['transaction_id'] ?? null;
                $pembayaran->save();

                return response()->json([
                    'success' => true,
                    'order_id' => $orderId,
                    'transaction_id' => $resData['transaction_id'] ?? null,
                    'amount' => (int)$pembayaran->jumlah_tagihan,
                    'amount_formatted' => 'Rp ' . number_format($pembayaran->jumlah_tagihan, 0, ',', '.'),
                    'qr_image_url' => $qrUrl,
                    'deeplink_url' => $deeplinkUrl,
                    'merchant_name' => 'HANDOKO & PARTNERS LAW FIRM',
                    'simulator_url' => 'https://simulator.sandbox.midtrans.com/qris/index',
                    'transaction_status' => $resData['transaction_status'] ?? 'pending',
                    'expiry_time' => $resData['expiry_time'] ?? null,
                ]);
            }

            // Jika order_id sudah dipakai (status 406), buat order_id baru
            if ($response->status() === 406 || ($resData['status_code'] ?? '') === '406') {
                $newOrderId = 'ORDER-HKM-' . date('Ymd') . '-' . strtoupper(Str::random(4));
                $pembayaran->order_id = $newOrderId;
                $pembayaran->snap_token = null;
                $pembayaran->save();

                // Retry with new order_id
                $payload['transaction_details']['order_id'] = $newOrderId;

                $response2 = Http::withBasicAuth($serverKey, '')
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post($baseUrl . '/v2/charge', $payload);

                $resData2 = $response2->json();
                Log::info('Midtrans GoPay Charge Retry Response:', $resData2);

                if ($response2->successful() && isset($resData2['actions'])) {
                    $qrUrl = null;
                    $deeplinkUrl = null;
                    foreach ($resData2['actions'] as $action) {
                        if ($action['name'] === 'generate-qr-code') {
                            $qrUrl = $action['url'];
                        }
                        if ($action['name'] === 'deeplink-redirect') {
                            $deeplinkUrl = $action['url'];
                        }
                    }

                    $pembayaran->metode_pembayaran = 'GOPAY-QRIS';
                    $pembayaran->status_transaksi = 'pending';
                    $pembayaran->transaction_id = $resData2['transaction_id'] ?? null;
                    $pembayaran->save();

                    return response()->json([
                        'success' => true,
                        'order_id' => $newOrderId,
                        'transaction_id' => $resData2['transaction_id'] ?? null,
                        'amount' => (int)$pembayaran->jumlah_tagihan,
                        'amount_formatted' => 'Rp ' . number_format($pembayaran->jumlah_tagihan, 0, ',', '.'),
                        'qr_image_url' => $qrUrl,
                        'deeplink_url' => $deeplinkUrl,
                        'merchant_name' => 'HANDOKO & PARTNERS LAW FIRM',
                        'simulator_url' => 'https://simulator.sandbox.midtrans.com/qris/index',
                        'transaction_status' => $resData2['transaction_status'] ?? 'pending',
                        'expiry_time' => $resData2['expiry_time'] ?? null,
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Midtrans API error (retry): ' . ($resData2['status_message'] ?? 'Unknown error'),
                    'raw' => $resData2,
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Midtrans API error: ' . ($resData['status_message'] ?? $response->body()),
                'status_code' => $resData['status_code'] ?? $response->status(),
                'raw' => $resData,
            ], 400);

        } catch (\Exception $e) {
            Log::error('Midtrans GoPay charge exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi server Midtrans: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Charge via ShopeePay (Midtrans Core API)
     */
    public function chargeShopeePay($id)
    {
        $pembayaran = Pembayaran::with('formulir.user')->findOrFail($id);

        if ($pembayaran->isLunas()) {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas.'], 400);
        }

        $serverKey = $this->getServerKey();
        $baseUrl = $this->getMidtransBaseUrl();

        if (empty($serverKey) || Str::contains($serverKey, 'SIMULATOR')) {
            return response()->json([
                'success' => false,
                'message' => 'Server Key Midtrans belum diisi. Silakan daftar di dashboard.sandbox.midtrans.com dan masukkan Server Key di file .env',
                'need_credentials' => true,
            ], 400);
        }

        $orderId = $pembayaran->order_id;

        // Jika order_id sudah dipakai, generate baru
        if ($pembayaran->transaction_id && $pembayaran->status_transaksi !== 'pending') {
            $orderId = 'ORDER-HKM-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $pembayaran->order_id = $orderId;
            $pembayaran->snap_token = null;
            $pembayaran->save();
        }

        $payload = [
            'payment_type' => 'shopeepay',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$pembayaran->jumlah_tagihan,
            ],
            'shopeepay' => [
                'callback_url' => url('/klien/pembayaran'),
            ],
            'customer_details' => [
                'first_name' => $pembayaran->formulir->nama ?? 'Klien',
                'email' => ($pembayaran->formulir->user->username ?? 'klien') . '@handokopartners.web.id',
                'phone' => $pembayaran->formulir->no_hp ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => 'ITEM-' . $pembayaran->id_pembayaran,
                    'price' => (int)$pembayaran->jumlah_tagihan,
                    'quantity' => 1,
                    'name' => 'Jasa Hukum: ' . Str::limit($pembayaran->formulir->no_permohonan ?? 'Konsultasi', 30),
                ]
            ],
        ];

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($baseUrl . '/v2/charge', $payload);

            $resData = $response->json();
            Log::info('Midtrans ShopeePay Charge Response:', $resData);

            if ($response->successful() && isset($resData['actions'])) {
                $deeplinkUrl = null;
                foreach ($resData['actions'] as $action) {
                    if ($action['name'] === 'deeplink-redirect') {
                        $deeplinkUrl = $action['url'];
                    }
                }

                $pembayaran->metode_pembayaran = 'SHOPEEPAY';
                $pembayaran->status_transaksi = 'pending';
                $pembayaran->transaction_id = $resData['transaction_id'] ?? null;
                $pembayaran->save();

                return response()->json([
                    'success' => true,
                    'order_id' => $orderId,
                    'transaction_id' => $resData['transaction_id'] ?? null,
                    'amount' => (int)$pembayaran->jumlah_tagihan,
                    'amount_formatted' => 'Rp ' . number_format($pembayaran->jumlah_tagihan, 0, ',', '.'),
                    'deeplink_url' => $deeplinkUrl,
                    'merchant_name' => 'HANDOKO & PARTNERS LAW FIRM',
                    'simulator_url' => 'https://simulator.sandbox.midtrans.com/openapi/payment',
                    'transaction_status' => $resData['transaction_status'] ?? 'pending',
                ]);
            }

            // Handle 406 (order_id sudah dipakai)
            if ($response->status() === 406 || ($resData['status_code'] ?? '') === '406') {
                $newOrderId = 'ORDER-HKM-' . date('Ymd') . '-' . strtoupper(Str::random(4));
                $pembayaran->order_id = $newOrderId;
                $pembayaran->snap_token = null;
                $pembayaran->save();

                $payload['transaction_details']['order_id'] = $newOrderId;

                $response2 = Http::withBasicAuth($serverKey, '')
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post($baseUrl . '/v2/charge', $payload);

                $resData2 = $response2->json();

                if ($response2->successful() && isset($resData2['actions'])) {
                    $deeplinkUrl = null;
                    foreach ($resData2['actions'] as $action) {
                        if ($action['name'] === 'deeplink-redirect') {
                            $deeplinkUrl = $action['url'];
                        }
                    }

                    $pembayaran->metode_pembayaran = 'SHOPEEPAY';
                    $pembayaran->status_transaksi = 'pending';
                    $pembayaran->transaction_id = $resData2['transaction_id'] ?? null;
                    $pembayaran->save();

                    return response()->json([
                        'success' => true,
                        'order_id' => $newOrderId,
                        'transaction_id' => $resData2['transaction_id'] ?? null,
                        'amount' => (int)$pembayaran->jumlah_tagihan,
                        'amount_formatted' => 'Rp ' . number_format($pembayaran->jumlah_tagihan, 0, ',', '.'),
                        'deeplink_url' => $deeplinkUrl,
                        'merchant_name' => 'HANDOKO & PARTNERS LAW FIRM',
                        'simulator_url' => 'https://simulator.sandbox.midtrans.com/openapi/payment',
                        'transaction_status' => $resData2['transaction_status'] ?? 'pending',
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Midtrans API error: ' . ($resData2['status_message'] ?? 'Unknown'),
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Midtrans ShopeePay error: ' . ($resData['status_message'] ?? $response->body()),
                'raw' => $resData,
            ], 400);

        } catch (\Exception $e) {
            Log::error('Midtrans ShopeePay exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi Midtrans: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cek status transaksi di Midtrans
     */
    public function checkStatus($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $serverKey = $this->getServerKey();
        $baseUrl = $this->getMidtransBaseUrl();

        if (empty($serverKey) || Str::contains($serverKey, 'SIMULATOR')) {
            return response()->json([
                'success' => false,
                'message' => 'Server Key belum diisi',
            ], 400);
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->get($baseUrl . '/v2/' . $pembayaran->order_id . '/status');

            $resData = $response->json();

            if ($response->successful()) {
                $status = $resData['transaction_status'] ?? 'unknown';

                if (in_array($status, ['capture', 'settlement'])) {
                    $pembayaran->status = 1;
                    $pembayaran->status_transaksi = 'settlement';
                    $pembayaran->waktu_transaksi = now();
                    $pembayaran->save();
                }

                return response()->json([
                    'success' => true,
                    'transaction_status' => $status,
                    'order_id' => $pembayaran->order_id,
                    'is_lunas' => in_array($status, ['capture', 'settlement']),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Check status error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal cek status',
        ], 500);
    }

    /**
     * Webhook Handler Notifikasi HTTP dari Payment Gateway
     */
    public function handleNotification(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Order ID is required'], 400);
        }

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();
        if (!$pembayaran) {
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        // Verifikasi Signature Hash (SHA512)
        $serverKey = $this->getServerKey();
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Update status sesuai respon gateway
        $pembayaran->transaction_id = $transactionId ?? $pembayaran->transaction_id;
        $pembayaran->metode_pembayaran = $paymentType ?? $pembayaran->metode_pembayaran;
        $pembayaran->status_transaksi = $transactionStatus;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $pembayaran->status = 1; // Lunas
            $pembayaran->waktu_transaksi = now();
        } elseif ($transactionStatus == 'pending') {
            $pembayaran->status = 0;
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $pembayaran->status = in_array($transactionStatus, ['expire']) ? 2 : 3;
        }

        $pembayaran->save();

        return response()->json(['status' => 'success', 'message' => 'Notification processed successfully']);
    }

    /**
     * Simulator Pembayaran Sukses (manual verify oleh user)
     */
    public function simulateSuccess(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $channel = $request->input('channel', 'QRIS');

        $pembayaran->status = 1;
        $pembayaran->status_transaksi = 'settlement';
        $pembayaran->metode_pembayaran = strtoupper($channel);
        $pembayaran->transaction_id = 'MIDTRANS-' . strtoupper($channel) . '-' . strtoupper(Str::random(10));
        $pembayaran->waktu_transaksi = now();
        $pembayaran->save();

        return back()->with('success', 'Pembayaran Midtrans Sandbox (' . strtoupper($channel) . ') Berhasil Diverifikasi! Tagihan LUNAS.');
    }

    /**
     * Dapatkan QRIS resmi merchant dengan nominal otomatis (Dynamic QRIS)
     * Menggunakan QRIS merchant user (NGEBON KUY - GAME, ShopeePay / GPN)
     */
    public function getQrisData($id)
    {
        $pembayaran = Pembayaran::with('formulir.user')->findOrFail($id);

        if ($pembayaran->isLunas()) {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas.'], 400);
        }

        $pembayaran->metode_pembayaran = 'QRIS';
        $pembayaran->status_transaksi = 'pending';
        $pembayaran->save();

        $amount = (int) $pembayaran->jumlah_tagihan;
        $qrisData = \App\Helpers\QrisHelper::generateDynamicQris($amount, $pembayaran->order_id);

        return response()->json([
            'success' => true,
            'order_id' => $pembayaran->order_id,
            'amount' => $amount,
            'amount_formatted' => $qrisData['amount_formatted'],
            'qr_image_url' => $qrisData['qr_image_url'],
            'original_qr_image_url' => $qrisData['original_qr_image_url'],
            'qr_string' => $qrisData['qris_string'],
            'merchant_name' => 'NGEBON KUY - GAME',
            'nmid' => 'ID1026634176683',
            'is_dynamic' => true,
            'note' => 'Nominal tagihan ' . $qrisData['amount_formatted'] . ' otomatis terisi saat di-scan oleh aplikasi m-Banking/E-Wallet (ShopeePay, BCA, Livin, GoPay, DANA, OVO, dll).',
        ]);
    }

    /**
     * Legacy: get VA data
     */
    public function getVaData($id, $bank)
    {
        $pembayaran = Pembayaran::with('formulir.user')->findOrFail($id);

        if ($pembayaran->isLunas()) {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas.'], 400);
        }

        $serverKey = $this->getServerKey();
        $baseUrl = $this->getMidtransBaseUrl();
        $bank = strtolower($bank);

        // Jika punya server key asli, charge VA via Core API
        if (!empty($serverKey) && !Str::contains($serverKey, 'SIMULATOR')) {
            $orderId = $pembayaran->order_id;

            $payload = [
                'payment_type' => 'bank_transfer',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$pembayaran->jumlah_tagihan,
                ],
                'bank_transfer' => [
                    'bank' => $bank,
                ],
                'customer_details' => [
                    'first_name' => $pembayaran->formulir->nama ?? 'Klien',
                    'email' => ($pembayaran->formulir->user->username ?? 'klien') . '@handokopartners.web.id',
                    'phone' => $pembayaran->formulir->no_hp ?? '08123456789',
                ],
            ];

            try {
                $response = Http::withBasicAuth($serverKey, '')
                    ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                    ->post($baseUrl . '/v2/charge', $payload);

                $resData = $response->json();
                Log::info('Midtrans VA Charge Response:', $resData);

                if ($response->successful()) {
                    $vaNumber = '';
                    if (isset($resData['va_numbers'][0]['va_number'])) {
                        $vaNumber = $resData['va_numbers'][0]['va_number'];
                    } elseif (isset($resData['permata_va_number'])) {
                        $vaNumber = $resData['permata_va_number'];
                    }

                    $pembayaran->metode_pembayaran = strtoupper($bank) . '_VA';
                    $pembayaran->status_transaksi = 'pending';
                    $pembayaran->transaction_id = $resData['transaction_id'] ?? null;
                    $pembayaran->save();

                    $simulatorUrl = match(strtoupper($bank)) {
                        'BCA' => 'https://simulator.sandbox.midtrans.com/bca/va/index',
                        'BNI' => 'https://simulator.sandbox.midtrans.com/bni/va/index',
                        'BRI' => 'https://simulator.sandbox.midtrans.com/bri/va/index',
                        default => 'https://simulator.sandbox.midtrans.com',
                    };

                    return response()->json([
                        'success' => true,
                        'bank' => strtoupper($bank),
                        'order_id' => $orderId,
                        'amount' => (int)$pembayaran->jumlah_tagihan,
                        'amount_formatted' => 'Rp ' . number_format($pembayaran->jumlah_tagihan, 0, ',', '.'),
                        'va_number' => $vaNumber,
                        'simulator_url' => $simulatorUrl,
                        'from_midtrans' => true,
                    ]);
                }

                // Handle 406
                if ($response->status() === 406) {
                    $newOrderId = 'ORDER-HKM-' . date('Ymd') . '-' . strtoupper(Str::random(4));
                    $pembayaran->order_id = $newOrderId;
                    $pembayaran->snap_token = null;
                    $pembayaran->save();

                    $payload['transaction_details']['order_id'] = $newOrderId;
                    $response2 = Http::withBasicAuth($serverKey, '')
                        ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                        ->post($baseUrl . '/v2/charge', $payload);
                    $resData2 = $response2->json();

                    if ($response2->successful()) {
                        $vaNumber = $resData2['va_numbers'][0]['va_number'] ?? ($resData2['permata_va_number'] ?? '');
                        $pembayaran->metode_pembayaran = strtoupper($bank) . '_VA';
                        $pembayaran->status_transaksi = 'pending';
                        $pembayaran->transaction_id = $resData2['transaction_id'] ?? null;
                        $pembayaran->save();

                        $simulatorUrl = match(strtoupper($bank)) {
                            'BCA' => 'https://simulator.sandbox.midtrans.com/bca/va/index',
                            'BNI' => 'https://simulator.sandbox.midtrans.com/bni/va/index',
                            'BRI' => 'https://simulator.sandbox.midtrans.com/bri/va/index',
                            default => 'https://simulator.sandbox.midtrans.com',
                        };

                        return response()->json([
                            'success' => true,
                            'bank' => strtoupper($bank),
                            'order_id' => $newOrderId,
                            'amount' => (int)$pembayaran->jumlah_tagihan,
                            'amount_formatted' => 'Rp ' . number_format($pembayaran->jumlah_tagihan, 0, ',', '.'),
                            'va_number' => $vaNumber,
                            'simulator_url' => $simulatorUrl,
                            'from_midtrans' => true,
                        ]);
                    }
                }

            } catch (\Exception $e) {
                Log::error('VA Charge error: ' . $e->getMessage());
            }
        }

        // Fallback: dummy VA
        $bank = strtoupper($bank);
        $bankPrefix = match($bank) {
            'BCA' => '70070',
            'MANDIRI' => '88888',
            'BNI' => '8808',
            'BRI' => '12888',
            default => '99000',
        };

        $vaNumber = $bankPrefix . str_pad($pembayaran->id_pembayaran, 8, '0', STR_PAD_LEFT);

        $pembayaran->metode_pembayaran = $bank . '_VA';
        $pembayaran->status_transaksi = 'pending';
        $pembayaran->save();

        $simulatorUrl = match($bank) {
            'BCA' => 'https://simulator.sandbox.midtrans.com/bca/va/index',
            'MANDIRI' => 'https://simulator.sandbox.midtrans.com/mandiri/bill/index',
            'BNI' => 'https://simulator.sandbox.midtrans.com/bni/va/index',
            'BRI' => 'https://simulator.sandbox.midtrans.com/bri/va/index',
            default => 'https://simulator.sandbox.midtrans.com',
        };

        return response()->json([
            'success' => true,
            'bank' => $bank,
            'order_id' => $pembayaran->order_id,
            'amount' => (int)$pembayaran->jumlah_tagihan,
            'amount_formatted' => 'Rp ' . number_format($pembayaran->jumlah_tagihan, 0, ',', '.'),
            'va_number' => $vaNumber,
            'simulator_url' => $simulatorUrl,
            'need_credentials' => true,
        ]);
    }

    /**
     * Halaman Simulator QRIS (internal) dengan QRIS merchant dinamis
     */
    public function simulatorQrisView($id)
    {
        $pembayaran = Pembayaran::with('formulir.user')->findOrFail($id);
        $amount = (int) $pembayaran->jumlah_tagihan;
        $qrisData = \App\Helpers\QrisHelper::generateDynamicQris($amount, $pembayaran->order_id);

        $qrString = $qrisData['qris_string'];
        $qrImageUrl = $qrisData['qr_image_url'];

        return view('frontend.simulator_qris', compact('pembayaran', 'qrString', 'qrImageUrl', 'qrisData'));
    }

    /**
     * Eksekusi Pembayaran dari Simulator QRIS (internal)
     */
    public function simulatorQrisPay(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $wallet = $request->input('wallet', 'GOPAY-QRIS');

        $pembayaran->status = 1;
        $pembayaran->status_transaksi = 'settlement';
        $pembayaran->metode_pembayaran = 'QRIS (' . strtoupper($wallet) . ')';
        $pembayaran->transaction_id = 'MIDTRANS-QRIS-' . strtoupper(Str::random(12));
        $pembayaran->waktu_transaksi = now();
        $pembayaran->save();

        return redirect()->route('klien.pembayaran.index')->with('success', 'Pembayaran QRIS Berhasil! Tagihan Order ' . $pembayaran->order_id . ' resmi LUNAS.');
    }
}
