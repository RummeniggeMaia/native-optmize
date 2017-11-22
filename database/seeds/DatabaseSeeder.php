<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->delete();
        User::create(array(
            'name' => 'Usuario Teste',
            'email' => 'teste@mail.com',
            'password' => Hash::make('teste'),
        ));
        DB::table('categories')->delete();
        Category::create(array(
            'name' => 'Category 1',
            'fixed' => true
        ));
        Category::create(array(
            'name' => 'Category 2',
            'fixed' => true
        ));
        Category::create(array(
            'name' => 'Category 3',
            'fixed' => true
        ));
    }

}
