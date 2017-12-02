<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Category;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('roles')->delete();
        Role::create(array(
            'name' => 'user'
        ));
        Role::create(array(
            'name' => 'admin'
        ));
        
        $role_admin = Role::where('name', 'admin')->first();
        $role_user = Role::where('name', 'user')->first();
        
        DB::table('users')->delete();
        $usuario = new User();
        $usuario->name = 'Admin Teste';
        $usuario->email = 'admin@adm.in';
        $usuario->password = Hash::make('qwerty');
        $usuario->save();
        $usuario->roles()->attach($role_admin);
        
        $usuario = new User();
        $usuario->name = 'Usuário Teste';
        $usuario->email = 'user@us.er';
        $usuario->password = Hash::make('qwerty');
        $usuario->save();
        $usuario->roles()->attach($role_user);
        
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
