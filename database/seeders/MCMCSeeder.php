<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MCMCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test MCMC user
        \App\Models\MCMC::create([
            'M_ID' => 'M000001',
            'M_Name' => 'MCMC Admin',
            'M_userName' => 'mcmcadmin',
            'M_Address' => 'MCMC Headquarters, Cyberjaya',
            'M_Email' => 'admin@mcmc.gov.my',
            'M_PhoneNum' => '0312345678',
            'M_Position' => 'Senior Officer',
            'M_Password' => 'password123', // Will be hashed by mutator
        ]);

        // Create another test MCMC user
        \App\Models\MCMC::create([
            'M_ID' => 'M000002',
            'M_Name' => 'MCMC Staff',
            'M_userName' => 'mcmcstaff',
            'M_Address' => 'MCMC Branch Office, Kuala Lumpur',
            'M_Email' => 'staff@mcmc.gov.my',
            'M_PhoneNum' => '0387654321',
            'M_Position' => 'Officer',
            'M_Password' => 'password123', // Will be hashed by mutator
        ]);
    }
}
