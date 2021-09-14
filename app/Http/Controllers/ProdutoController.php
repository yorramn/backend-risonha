<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    public function index()
    {
        if(count(Produto::all()) > 0){
            return response([
                'produtos'=>Produto::all()
            ],200);
        }else{
            return response([
                'mensagem'=>'Não há produtos cadastrados'
            ],500);
        }
    }


    public function store(Request $request)
    {
        $attrs = $request->validate([
           'codigo' => 'string|required',
           'nome' => 'string|required',
           'data_de_validade' => 'required',
            'quantidade' => 'integer|required|min:1',
            'tipo_de_quantidade' => 'string|required',
            'peso' => 'required',
            'tipo_de_peso' => 'string|required',
            'fabricante' => 'string|required',
            'preco' => 'required',
        ]);
        if(isset($request->categoria_id)){
            $produto = Produto::create([
                'codigo' => $attrs['codigo'],
                'nome' => $attrs['nome'],
                'data_de_validade' => $attrs['data_de_validade'],
                'quantidade' => $attrs['quantidade'],
                'tipo_de_quantidade' => $attrs['tipo_de_quantidade'],
                'peso' => $attrs['peso'],
                'tipo_de_peso' => $attrs['tipo_de_peso'],
                'fabricante' => $attrs['fabricante'],
                'preco' => $attrs['preco'],
                'categoria_id' => $request->categoria_id,
                'user_id' => auth()->user()->id
            ]);
            if($produto){
                return response([
                    'message' => 'Produto cadastrado com sucesso',
                    'produto' => $produto
                ],200);
            }else{
                return response([
                    'message' => 'Erro ao cadastrar o produto!',
                    'produto' => $produto
                ],500);
            }
        }else{
            return response([
                'message' => 'Erro ao cadastrar o produto! Categoria ausente ou não encontrada'
            ],402);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Produto::find($id);
        if(isset($produto)){
            return Controller::retornarConteudo(null,$produto,200);
        }else{
            return Controller::retornarConteudo('Produto não encontrado',null,404);
        }
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        if(isset($produto)){
            $attrs = $request->validate([
                'codigo' => 'string',
                'nome' => 'string',
                'data_de_validade' => 'date',
                'quantidade' => 'integer|min:1',
                'tipo_de_quantidade' => 'string',
                'peso' => 'string',
                'tipo_de_peso' => 'string',
                'fabricante' => 'string',
                'preco' => 'string',
            ]);
            $produto->update([
                'codigo' => $attrs['codigo'],
                'nome' => $attrs['nome'],
                'data_de_validade' => $attrs['data_de_validade'],
                'quantidade' => $attrs['quantidade'],
                'tipo_de_quantidade' => $attrs['tipo_de_quantidade'],
                'peso' => $attrs['peso'],
                'tipo_de_peso' => $attrs['tipo_de_peso'],
                'fabricante' => $attrs['fabricante'],
                'preco' => $attrs['preco'],
                'categoria_id' => $request->categoria_id,
                'user_id' => auth()->user()->id
            ]);
            return Controller::retornarConteudo('Produto atualizado com sucesso',$produto,200);
        }else{
            return Controller::retornarConteudo('Produto não encontrado',null,401);
        }
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        if(isset($produto)){
            if($produto->delete()){
                return Controller::retornarConteudo('Produto deletado com sucesso!',$produto,200);
            }else{
                return Controller::retornarConteudo('Erro ao deletar o produto!',$produto,402);
            }
        }else{
            return Controller::retornarConteudo('Produto não encontrado!',null,405);
        }
    }
}
