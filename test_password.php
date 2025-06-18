<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PublicUser;
use Illuminate\Support\Facades\Hash;

$user = PublicUser::first();
echo "Testing passwords for user: " . $user->PU_Email . "\n";

$passwords = ['password', '123456', 'password123', 'admin', 'test', '12345678', 'afiqah123'];

foreach($passwords as $pwd) {
    if(Hash::check($pwd, $user->PU_Password)) {
        echo "✅ Password found: " . $pwd . "\n";
        exit;
    }
}

echo "❌ None of the common passwords work\n";
echo "Password hash: " . $user->PU_Password . "\n";