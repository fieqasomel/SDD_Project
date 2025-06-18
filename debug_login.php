<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\MCMC;
use Illuminate\Support\Facades\Hash;

echo "=== LOGIN DEBUG ===\n\n";

// Test PublicUser
echo "1. Testing PublicUser Login:\n";
$email = 'test@example.com';
$password = 'password123';
$user = PublicUser::where('PU_Email', $email)->first();

if ($user) {
    echo "✅ User found: " . $user->PU_Name . "\n";
    echo "Email: " . $user->PU_Email . "\n";
    echo "Password check: " . (Hash::check($password, $user->getAuthPassword()) ? "✅ PASS" : "❌ FAIL") . "\n";
    echo "Auth Password: " . substr($user->getAuthPassword(), 0, 20) . "...\n";
} else {
    echo "❌ User not found\n";
}

echo "\n";

// Test Agency
echo "2. Testing Agency Login:\n";
$username = 'testagency';
$password = 'password123';
$agency = Agency::where('A_userName', $username)->first();

if ($agency) {
    echo "✅ Agency found: " . $agency->A_Name . "\n";
    echo "Username: " . $agency->A_userName . "\n";
    echo "Password check: " . (Hash::check($password, $agency->getAuthPassword()) ? "✅ PASS" : "❌ FAIL") . "\n";
    echo "Auth Password: " . substr($agency->getAuthPassword(), 0, 20) . "...\n";
} else {
    echo "❌ Agency not found\n";
}

echo "\n";

// Test MCMC
echo "3. Testing MCMC Login:\n";
$username = 'testmcmc';
$password = 'password123';
$mcmc = MCMC::where('M_userName', $username)->first();

if ($mcmc) {
    echo "✅ MCMC found: " . $mcmc->M_Name . "\n";
    echo "Username: " . $mcmc->M_userName . "\n";
    echo "Password check: " . (Hash::check($password, $mcmc->getAuthPassword()) ? "✅ PASS" : "❌ FAIL") . "\n";
    echo "Auth Password: " . substr($mcmc->getAuthPassword(), 0, 20) . "...\n";
} else {
    echo "❌ MCMC not found\n";
}

echo "\n=== SUMMARY ===\n";
echo "If all password checks show ✅ PASS, then the issue is likely in:\n";
echo "1. Session configuration\n";
echo "2. Middleware issues\n";
echo "3. Route problems\n";
echo "4. Frontend form issues\n";