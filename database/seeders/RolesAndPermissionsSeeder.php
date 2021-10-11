<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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

        // this can be done as separate statements
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $estoquistaRole = Role::create(['name' => 'estoquista']);
        $estoquistaRole->givePermissionTo([
            'cadastrar produto',
            'editar produto',
            'visualizar produto',
            'visualizar produtos',

            'cadastrar categoria',
            'editar categoria',
            'visualizar categoria',
            'visualizar categorias',
        ]);

        $balconistaRole = Role::create(['name' => 'balconista']);
        $balconistaRole->givePermissionTo([
            'cadastrar venda',
            'editar venda',
            'visualizar venda',
            'visualizar vendas',

            'cadastrar encomenda',
            'editar encomenda',
            'visualizar encomenda',
            'visualizar encomendas',

            'cadastrar promocao',
            'editar promocao',
            'visualizar promocao',
            'visualizar promocaos',

            'cadastrar cliente',
            'editar cliente',
            'visualizar cliente',
            'visualizar clientes',
        ]);


        User::insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678')
        ]);

        User::insert([
            'name' => 'estoquista',
            'email' => 'estoquista@estoquista.com',
            'password' => bcrypt('12345678')
        ]);

        User::insert([
            'name' => 'balconista',
            'email' => 'balconista@balconista.com',
            'password' => bcrypt('12345678')
        ]);

        $admin = User::where('name','admin')->get()->first();
        $admin->assignRole($adminRole);

        $estoquista = User::where('name','estoquista')->get()->first();
        $estoquista->assignRole($estoquistaRole);


        $balconista = User::where('name','balconista')->get()->first();
        $balconista->assignRole($balconistaRole);
    }
}
