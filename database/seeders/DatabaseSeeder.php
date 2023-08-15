<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utility;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (\Request::route()->getName() != 'LaravelUpdater::database') {
            $this->call(UsersTableSeeder::class);
            $this->call(NotificationSeeder::class);
            $this->call(AiTemplateSeeder::class);
            Artisan::call('module:migrate LandingPage');
            Artisan::call('module:seed LandingPage');
        } else {
            Utility::languagecreate();
        }
    }
}