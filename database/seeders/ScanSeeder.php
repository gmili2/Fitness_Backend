<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scan;

class ScanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Scan::factory()->count(10)->create();
    }
}
