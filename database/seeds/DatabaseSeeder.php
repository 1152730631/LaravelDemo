<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        //\Illuminate\Database\Eloquent\Model::unguard();
        //$this->call('UserTabelSeeder');
        //\Illuminate\Database\Eloquent\Model::reguard();

        \Illuminate\Database\Eloquent\Model::unguard();
        $this->call('UsersTableSeeder');
        $this->call('StatusesTableSeeder');
        $this->call('FollowersTableSeeder');
        \Illuminate\Database\Eloquent\Model::reguard();

    }
}
