<?php

namespace Modules\Auth\Database\Seeders;

use App\Imports\ProfessionsImport;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class ProfessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filePath = base_path('/data/Categories.xlsx');

        Excel::import(new ProfessionsImport, $filePath);
    }
}
