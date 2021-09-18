<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        $role = Role::all();
        if(count($role) > 0){
            return Controller::retornarConteudo(null,$role,200);
        }else{
            return Controller::retornarConteudo('Nehum cargo foi registrado ainda',null,406);
        }
    }


    public function store(Request $request)
    {

        $attrs = $request->validate([
            'name' => 'required|string',
            'permissions' => 'array|required'
        ]);
        $role = Role::create([
            'name' => $attrs['name'],
        ]);
        if($role->syncPermissions($attrs['permissions'])){
            return Controller::retornarConteudo('Cargo criado com sucesso',$role,200);
        }else{
            return Controller::retornarConteudo('Erro ao criar o cargo',null,406);
        }
    }

    public function show($id)
    {
        try {
            $role = Role::findById($id);
            return Controller::retornarConteudo(null, $role, 200);
        }
        catch(exception $e){
            return Controller::retornarConteudo('Erro ao buscar o cargo',null,500);
        }
    }


    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        if($role){
            $attrs = $request->validate([
                'name' => 'string',
                'permissions' => 'array'
            ]);
            $role->syncPermissions($attrs['permissions']);
            if($role->save()){
                return Controller::retornarConteudo('Cargo atualizado com sucesso',$role,200);
            }else{
                return Controller::retornarConteudo('Erro ao atualizar o cargo',$role,406);
            }
        }else{
            return Controller::retornarConteudo('Erro ao atualizar o cargo',null,500);
        }
    }

    public function destroy($id)
    {
        $role = Role::findById($id);
        if($role){
            if($role->delete()){
                return Controller::retornarConteudo('Cargo deletado com sucesso',$role,200);
            }else{
                return Controller::retornarConteudo('Erro ao deletar o cargo',$role,406);
            }
        }else{
            return Controller::retornarConteudo('Erro ao deletar o cargo',null,500);
        }
    }
}
