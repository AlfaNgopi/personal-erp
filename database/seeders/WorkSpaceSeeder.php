<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\WorkSpace;
use Illuminate\Database\Seeder;

class WorkSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Main Workspace',
                'base_currency' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'status' => 'active'
            ],
            
        ];

        foreach ($data as $item) {
            $ws = WorkSpace::create($item);
            
        }
    }
}
