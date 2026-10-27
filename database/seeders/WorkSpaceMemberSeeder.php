<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkSpaceMember;

class WorkSpaceMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'work_space_id' => 5, 
                'user_id' => 1,
                'role' => 'owner',
                'status' => 'active'
            ]
        ];
        foreach ($data as $item) {
            WorkSpaceMember::create($item);
        }
    }
}
