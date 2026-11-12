<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$clients = [
    ['username' => 'klien2', 'name' => 'Budi Santoso', 'password' => 'password123'],
    ['username' => 'klien3', 'name' => 'Siti Nurhaliza', 'password' => 'password123'],
    ['username' => 'klien4', 'name' => 'Andi Wijaya', 'password' => 'password123'],
];

foreach ($clients as $data) {
    // cek bila sudah ada
    $exists = User::where('username', $data['username'])->first();
    if ($exists) {
        echo "User {$data['username']} sudah ada, dilewati.\n";
        continue;
    }
    $user = User::create([
        'id_role' => 3, // klien
        'name' => $data['name'],
        'username' => $data['username'],
        'password' => Hash::make($data['password']),
        'image' => 'default.jpg',
        'is_active' => 1,
        'date_created' => time(),
    ]);
    echo "Created client {$data['username']} (ID: {$user->id_user})\n";
}
?>
