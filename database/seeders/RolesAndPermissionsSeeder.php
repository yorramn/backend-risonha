<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'cadastrar produto']);
        Permission::create(['name' => 'editar produto']);
        Permission::create(['name' => 'visualizar produto']);
        Permission::create(['name' => 'visualizar produtos']);
        Permission::create(['name' => 'deletar produto']);

        // create permissions
        Permission::create(['name' => 'cadastrar categoria']);
        Permission::create(['name' => 'editar categoria']);
        Permission::create(['name' => 'visualizar categoria']);
        Permission::create(['name' => 'visualizar categorias']);
        Permission::create(['name' => 'deletar categoria']);

        // create permissions
        Permission::create(['name' => 'cadastrar cliente']);
        Permission::create(['name' => 'editar cliente']);
        Permission::create(['name' => 'visualizar cliente']);
        Permission::create(['name' => 'visualizar clientes']);
        Permission::create(['name' => 'deletar cliente']);

        // create permissions
        Permission::create(['name' => 'cadastrar promocao']);
        Permission::create(['name' => 'editar promocao']);
        Permission::create(['name' => 'visualizar promocao']);
        Permission::create(['name' => 'visualizar promocaos']);
        Permission::create(['name' => 'deletar promocao']);

        // create permissions
        Permission::create(['name' => 'cadastrar venda']);
        Permission::create(['name' => 'editar venda']);
        Permission::create(['name' => 'visualizar venda']);
        Permission::create(['name' => 'visualizar vendas']);

        // create permissions
        Permission::create(['name' => 'cadastrar encomenda']);
        Permission::create(['name' => 'editar encomenda']);
        Permission::create(['name' => 'visualizar encomenda']);
        Permission::create(['name' => 'visualizar encomendas']);

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        // this can be done as separate statements
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $user->assignRole('admin');
    }
}
