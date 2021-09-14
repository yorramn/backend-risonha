<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{

    //método de retorno
    private function retorno($mensagem,$status,$objeto = null){
        return response([
            'mensagem' => $mensagem,
            'objeto' => $objeto
        ],$status);
    }

    public function index()
    {
        if(count(Categoria::all()) > 0){
            return response([
                'categorias' => Categoria::all()
            ],200);
        }else{
            return response([
                'mensagem'=>'Não há categorias cadastradas'
            ],500);
        }

    }

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'nome' => 'string|required',
            'descricao' => 'string|required'
        ]);
        $categoria = Categoria::create([
            'nome' => $attrs['nome'],
            'descricao' => $attrs['descricao'],
            'user_id' => auth()->user()->id
        ]);
        if(isset($categoria)){
            return $this->retorno('Categoria cadastrada com sucesso',201,$categoria);
        }else{
            return $this->retorno('Erro ao cadastrar a categoria',500);
        }
    }

    public function show($id)
    {
        $categoria = Categoria::find($id);
        if(isset($categoria)){
            return Controller::retornarConteudo(null, $categoria,200);
        }else{
            return Controller::retornarConteudo('Categoria não encontrada!', null,402);
        }
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        if(isset($categoria)){
            $attrs = $request->validate([
                'nome' => 'string',
                'descricao' => 'string'
            ]);
            $categoria->update([
                'nome' => $attrs['nome'],
                'descricao' => $attrs['descricao'],
                'user_id' => auth()->user()->id
            ]);
            return Controller::retornarConteudo('Categoria editada com sucesso',$categoria,200);
        }else{
            return Controller::retornarConteudo('Categoria não encontrada',null,402);
        }
    }

    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        if(isset($categoria)){
            if($categoria->delete()){
                return Controller::retornarConteudo('Categoria deletada com sucesso',$categoria,200);
            }else{
                return Controller::retornarConteudo('Erro ao deletar a categoria',null,402);
            }
        }else{
            return Controller::retornarConteudo('Categoria não encontrada',null,402);
        }
    }
}
