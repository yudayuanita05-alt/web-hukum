<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\User;
use Illuminate\Support\Facades\Hash;
$users = User::all();
foreach ($users as $user) {
    $user->password = Hash::make('password123');
    $user->save();
    echo "Reset password for {$user->username}\n";
}
?>
