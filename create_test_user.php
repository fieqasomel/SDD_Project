<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\MCMC;

// Create test PublicUser
$lastUser = PublicUser::orderBy('PU_ID', 'desc')->first();
$newId = $lastUser ? 'PU' . str_pad((intval(substr($lastUser->PU_ID, 2)) + 1), 5, '0', STR_PAD_LEFT) : 'PU00001';

$publicUser = PublicUser::create([
    'PU_ID' => $newId,
    'PU_Name' => 'Test User',
    'PU_IC' => '123456789012',
    'PU_Age' => 25,
    'PU_Address' => 'Test Address',
    'PU_Email' => 'test@example.com',
    'PU_PhoneNum' => '0123456789',
    'PU_Gender' => 'Male',
    'PU_Password' => 'password123',
]);

echo "✅ Created PublicUser: test@example.com / password123\n";

// Create test Agency
$lastAgency = Agency::orderBy('A_ID', 'desc')->first();
$newAgencyId = $lastAgency ? 'A' . str_pad((intval(substr($lastAgency->A_ID, 1)) + 1), 6, '0', STR_PAD_LEFT) : 'A000001';

$agency = Agency::create([
    'A_ID' => $newAgencyId,
    'A_Name' => 'Test Agency',
    'A_userName' => 'testagency',
    'A_Email' => 'agency@test.com',
    'A_Password' => 'password123',
    'A_PhoneNum' => '0123456789',
    'A_Address' => 'Test Address',
    'A_Category' => 'Government',
]);

echo "✅ Created Agency: testagency / password123\n";

// Create test MCMC
$lastMCMC = MCMC::orderBy('M_ID', 'desc')->first();
$newMCMCId = $lastMCMC ? 'M' . str_pad((intval(substr($lastMCMC->M_ID, 1)) + 1), 6, '0', STR_PAD_LEFT) : 'M000001';

$mcmc = MCMC::create([
    'M_ID' => $newMCMCId,
    'M_Name' => 'Test MCMC',
    'M_userName' => 'testmcmc',
    'M_Email' => 'mcmc@test.com',
    'M_Password' => 'password123',
    'M_PhoneNum' => '0123456789',
    'M_Address' => 'Test Address',
    'M_Position' => 'Admin',
]);

echo "✅ Created MCMC: testmcmc / password123\n";
echo "\n=== LOGIN CREDENTIALS ===\n";
echo "PublicUser: test@example.com / password123\n";
echo "Agency: testagency / password123\n";
echo "MCMC: testmcmc / password123\n";