<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Agency;
use App\Models\MCMC;
use App\Models\PublicUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=== DEBUG AUTHENTICATION ===" . PHP_EOL;

// Test MCMC user
echo PHP_EOL . "Testing MCMC Authentication:" . PHP_EOL;
$mcmc = MCMC::where('M_userName', 'testmcmc')->first();
if ($mcmc) {
    echo "✓ MCMC user found: " . $mcmc->M_userName . PHP_EOL;
    echo "✓ Email: " . $mcmc->M_Email . PHP_EOL;
    
    // Test password
    $testPassword = 'password123';
    if (Hash::check($testPassword, $mcmc->M_Password)) {
        echo "✓ Password verification SUCCESS" . PHP_EOL;
    } else {
        echo "✗ Password verification FAILED" . PHP_EOL;
    }
    
    // Test authentication methods
    echo "✓ Auth identifier name: " . $mcmc->getAuthIdentifierName() . PHP_EOL;
    echo "✓ Auth identifier: " . $mcmc->getAuthIdentifier() . PHP_EOL;
    echo "✓ Auth password: " . (strlen($mcmc->getAuthPassword()) > 0 ? 'Present' : 'Missing') . PHP_EOL;
} else {
    echo "✗ MCMC user not found" . PHP_EOL;
}

echo PHP_EOL . "Testing Agency Authentication:" . PHP_EOL;
$agency = Agency::where('A_userName', 'testagency')->first();
if ($agency) {
    echo "✓ Agency user found: " . $agency->A_userName . PHP_EOL;
    echo "✓ Email: " . $agency->A_Email . PHP_EOL;
    
    // Test password
    $testPassword = 'password123';
    if (Hash::check($testPassword, $agency->A_Password)) {
        echo "✓ Password verification SUCCESS" . PHP_EOL;
    } else {
        echo "✗ Password verification FAILED" . PHP_EOL;
    }
    
    // Test authentication methods
    echo "✓ Auth identifier name: " . $agency->getAuthIdentifierName() . PHP_EOL;
    echo "✓ Auth identifier: " . $agency->getAuthIdentifier() . PHP_EOL;
    echo "✓ Auth password: " . (strlen($agency->getAuthPassword()) > 0 ? 'Present' : 'Missing') . PHP_EOL;
} else {
    echo "✗ Agency user not found" . PHP_EOL;
}

echo PHP_EOL . "Testing PublicUser Authentication:" . PHP_EOL;
$publicUser = PublicUser::where('PU_Email', 'testuser@test.com')->first();
if ($publicUser) {
    echo "✓ PublicUser found: " . $publicUser->PU_Email . PHP_EOL;
    
    // Test password
    $testPassword = 'password123';
    if (Hash::check($testPassword, $publicUser->PU_Password)) {
        echo "✓ Password verification SUCCESS" . PHP_EOL;
    } else {
        echo "✗ Password verification FAILED" . PHP_EOL;
    }
} else {
    echo "✗ PublicUser not found" . PHP_EOL;
}

echo PHP_EOL . "=== END DEBUG ===" . PHP_EOL;