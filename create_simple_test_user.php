<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\MCMC;

// Create test PublicUser with simple ID
$publicUser = PublicUser::create([
    'PU_ID' => 'PU00999',
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
$agency = Agency::create([
    'A_ID' => 'A000999',
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
$mcmc = MCMC::create([
    'M_ID' => 'M000999',
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