<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Visitor;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Visitor::factory()->count(50)->create();
        Visitor::create([
            'name' => '検証用',
            'email' => 'gotoshigeki@gsacademy.jp',
        ]);
    }
}
