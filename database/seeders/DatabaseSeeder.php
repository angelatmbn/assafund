<?php

namespace Database\Seeders;

use App\Models\User;
<<<<<<< HEAD
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
=======
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> 01d5887bcbcf9fd3d88f46f56512d753d1918fbc
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
<<<<<<< HEAD
=======
    use WithoutModelEvents;

>>>>>>> 01d5887bcbcf9fd3d88f46f56512d753d1918fbc
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
