<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Agency;
use App\Models\MCMC;
use App\Models\PublicUser;

echo "=== Testing Login Data ===" . PHP_EOL;

// Check Agency
$agency = Agency::first();
if ($agency) {
    echo "Agency found:" . PHP_EOL;
    echo "- ID: " . $agency->A_ID . PHP_EOL;
    echo "- Username: " . $agency->A_userName . PHP_EOL;
    echo "- Email: " . $agency->A_Email . PHP_EOL;
    echo "- Password Hash: " . substr($agency->A_Password, 0, 20) . "..." . PHP_EOL;
} else {
    echo "No Agency found" . PHP_EOL;
}

echo PHP_EOL;

// Check MCMC
$mcmc = MCMC::first();
if ($mcmc) {
    echo "MCMC found:" . PHP_EOL;
    echo "- ID: " . $mcmc->M_ID . PHP_EOL;
    echo "- Username: " . $mcmc->M_userName . PHP_EOL;
    echo "- Email: " . $mcmc->M_Email . PHP_EOL;
    echo "- Password Hash: " . substr($mcmc->M_Password, 0, 20) . "..." . PHP_EOL;
} else {
    echo "No MCMC found" . PHP_EOL;
}

echo PHP_EOL;

// Check Public User
$publicUser = PublicUser::first();
if ($publicUser) {
    echo "Public User found:" . PHP_EOL;
    echo "- ID: " . $publicUser->PU_ID . PHP_EOL;
    echo "- Name: " . $publicUser->PU_Name . PHP_EOL;
    echo "- Email: " . $publicUser->PU_Email . PHP_EOL;
    echo "- Password Hash: " . substr($publicUser->PU_Password, 0, 20) . "..." . PHP_EOL;
} else {
    echo "No Public User found" . PHP_EOL;
}

echo PHP_EOL . "=== End Test ===" . PHP_EOL;