<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProfessionsImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $rows = $collection->map(function ($array) {
            $name = $array[0];
            if (! $name) return;

            return [
                'name' => $name,
            ];
        })->filter(
            fn($row) => !is_null($row)
        )->toArray();;

        DB::table('professions')->insert($rows);
    }
}
