<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Agency;
use App\Models\MCMC;
use App\Models\PublicUser;
use Illuminate\Support\Facades\Hash;

echo "=== Creating Test Users ===" . PHP_EOL;

// Create test Agency if not exists
$agency = Agency::where('A_userName', 'testagency')->first();
if (!$agency) {
    $agency = new Agency();
    $agency->A_ID = 'A999999';
    $agency->A_Name = 'Test Agency';
    $agency->A_userName = 'testagency';
    $agency->A_Email = 'testagency@test.com';
    $agency->A_Password = 'password123';
    $agency->A_PhoneNum = '0123456789';
    $agency->A_Address = 'Test Address';
    $agency->A_Category = 'Government';
    $agency->save();
    echo "Test Agency created - Username: testagency, Password: password123" . PHP_EOL;
} else {
    echo "Test Agency already exists" . PHP_EOL;
}

// Create test MCMC if not exists
$mcmc = MCMC::where('M_userName', 'testmcmc')->first();
if (!$mcmc) {
    $mcmc = new MCMC();
    $mcmc->M_ID = 'M999999';
    $mcmc->M_Name = 'Test MCMC';
    $mcmc->M_userName = 'testmcmc';
    $mcmc->M_Email = 'testmcmc@test.com';
    $mcmc->M_Password = 'password123';
    $mcmc->M_PhoneNum = '0123456789';
    $mcmc->M_Address = 'Test Address';
    $mcmc->M_Position = 'Staff';
    $mcmc->save();
    echo "Test MCMC created - Username: testmcmc, Password: password123" . PHP_EOL;
} else {
    echo "Test MCMC already exists" . PHP_EOL;
}

// Create test Public User if not exists
$publicUser = PublicUser::where('PU_Email', 'testuser@test.com')->first();
if (!$publicUser) {
    $publicUser = new PublicUser();
    $publicUser->PU_ID = 'PU99999';
    $publicUser->PU_Name = 'Test User';
    $publicUser->PU_IC = '1234567890';
    $publicUser->PU_Age = 25;
    $publicUser->PU_Address = 'Test Address';
    $publicUser->PU_Email = 'testuser@test.com';
    $publicUser->PU_PhoneNum = '0123456789';
    $publicUser->PU_Gender = 'Male';
    $publicUser->PU_Password = 'password123';
    $publicUser->save();
    echo "Test Public User created - Email: testuser@test.com, Password: password123" . PHP_EOL;
} else {
    echo "Test Public User already exists" . PHP_EOL;
}

echo "=== Test Users Ready ===" . PHP_EOL;
echo "You can now test login with:" . PHP_EOL;
echo "1. Agency - Username: testagency, Password: password123" . PHP_EOL;
echo "2. MCMC - Username: testmcmc, Password: password123" . PHP_EOL;
echo "3. Public User - Email: testuser@test.com, Password: password123" . PHP_EOL;