<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


class PermissionController extends Controller
{

    public function index()
    {
        $permission = Permission::all();
        if (count($permission) > 0) {
            return Controller::retornarConteudo(null, $permission, 200);
        } else {
            return Controller::retornarConteudo('Não há permissões cadastradas', null, 500);
        }
    }

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string',
        ]);
        $permission = Permission::create([
            'name' => $attrs['name'],
        ]);
        if ($permission) {
            return Controller::retornarConteudo('Permissão criado com sucesso', $permission, 200);
        } else {
            return Controller::retornarConteudo('Erro ao criar o Permissão', null, 406);
        }
    }

    public function show($id)
    {
        $permission = Permission::findById($id);
        if($permission){
            return Controller::retornarConteudo(null,$permission,200);
        }else{
            return Controller::retornarConteudo('Não foi possível encontrar a permissão',null,406);
        }
    }


    public function update(Request $request, $id)
    {
        $permission = Permission::findById($id);
        if($permission){
            $attrs = $request->validate([
                'name' => 'string',
            ]);
            $permission->name = $attrs['name'];
            if($permission->save()){
                return Controller::retornarConteudo('Permissão atualizado com sucesso',$permission,200);
            }else{
                return Controller::retornarConteudo('Erro ao atualizar o Permissão',$permission,406);
            }
        }else{
            return Controller::retornarConteudo('Erro ao atualizar o Permissão',null,500);
        }
    }


    public function destroy($id)
    {
        $permission = Permission::findById($id);
        if($permission){
            if($permission->delete()){
                return Controller::retornarConteudo('Permissão deletado com sucesso',$permission,200);
            }else{
                return Controller::retornarConteudo('Erro ao deletar o Permissão',$permission,406);
            }
        }else{
            return Controller::retornarConteudo('Erro ao deletar o Permissão',null,500);
        }
    }
}
