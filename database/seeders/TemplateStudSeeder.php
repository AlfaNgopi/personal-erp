<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateStudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Sample Name',
                'description' => 'Sample Description',
                'date' => '2024-06-01',
                'number' => 1,
                'enum' => 'option1',
            ]
        ];
        foreach ($data as $item) {
            DB::table('template_studs')->insert($item);
        }
    }
}
