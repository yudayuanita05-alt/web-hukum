<?php

namespace App\Helpers;

class QrisHelper
{
    /**
     * Base QRIS string resmi dari merchant (Static QRIS - Ngebon Kuy / ShopeePay)
     * 100% Valid ASPI / GPN / ShopeePay
     */
    const BASE_QRIS = '00020101021126610016ID.CO.SHOPEE.WWW01189360091800241183680208241183680303UMI51440014ID.CO.QRIS.WWW0215ID10266341766830303UMI5204581653033605802ID5910Ngebon kuy6013LAMPUNG TIMUR61053437362070703A0163043C08';

    /**
     * Parse QRIS EMVCo TLV format
     */
    public static function parseTlv(string $data): array
    {
        $result = [];
        $i = 0;
        $len = strlen($data);

        while ($i < $len - 4) {
            $tag = substr($data, $i, 2);
            $i += 2;
            $length = (int) substr($data, $i, 2);
            $i += 2;
            $value = substr($data, $i, $length);
            $i += $length;
            $result[$tag] = $value;
        }

        return $result;
    }

    /**
     * Build TLV string dari array
     */
    public static function buildTlv(array $tlvData): string
    {
        $result = '';
        ksort($tlvData);
        foreach ($tlvData as $tag => $value) {
            $result .= $tag . str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
        }
        return $result;
    }

    /**
     * Hitung CRC16-CCITT (standar QRIS/EMVCo)
     */
    public static function crc16(string $data): string
    {
        $crc = 0xFFFF;
        $polynomial = 0x1021;

        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Dapatkan QRIS Statis Asli (Bisa dibayar dengan uang nyata tanpa ditolak ShopeePay)
     */
    public static function getOriginalStaticQris(int $amount): array
    {
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=' . urlencode(self::BASE_QRIS);

        return [
            'qris_string' => self::BASE_QRIS,
            'qr_image_url' => $qrImageUrl,
            'amount' => $amount,
            'amount_formatted' => 'Rp ' . number_format($amount, 0, ',', '.'),
            'merchant_name' => 'Ngebon kuy',
            'nmid' => 'ID1026634176683',
            'mode' => 'static',
        ];
    }

    /**
     * Generate Static QRIS dengan nominal otomatis (Tag 01 tetap 11 agar tidak ditolak server ShopeePay)
     */
    public static function generateDynamicQris(int $amount, ?string $orderId = null): array
    {
        $tlv = self::parseTlv(self::BASE_QRIS);

        // Biarkan Tag 01 tetap "11" (Static) agar server ShopeePay tidak menolak transaksi
        $tlv['01'] = '11';

        // Tag 54: Transaction Amount
        $tlv['54'] = (string) $amount;

        // Tag 62 tetap utuh dari merchant asli
        $tlv['62'] = '0703A01';

        unset($tlv['63']);

        $qrisWithoutCrc = self::buildTlv($tlv);
        $qrisWithoutCrc .= '6304';
        $crc = self::crc16($qrisWithoutCrc);
        $qrisString = $qrisWithoutCrc . $crc;

        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=' . urlencode($qrisString);
        $originalQrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=' . urlencode(self::BASE_QRIS);

        return [
            'qris_string' => $qrisString,
            'qr_image_url' => $qrImageUrl,
            'original_qr_image_url' => $originalQrUrl,
            'amount' => $amount,
            'amount_formatted' => 'Rp ' . number_format($amount, 0, ',', '.'),
            'merchant_name' => 'Ngebon kuy',
            'nmid' => 'ID1026634176683',
            'mode' => 'static_with_amount',
        ];
    }
}
