<?php

namespace App\Http\Controllers;

use App\Models\Promocao;
use Illuminate\Http\Request;

class PromocaoController extends Controller
{

    public function index()
    {
        $promocao = Promocao::all();
        if(count($promocao) > 0){
            return Controller::retornarConteudo(null,$promocao,200);
        }else{
            return Controller::retornarConteudo('Nâo há promoções cadastradas no momento',null,406);
        }
    }


    public function store(Request $request)
    {


        $attrs = $request->validate([
            'codigo' => 'string|required',
            'descricao' => 'string|required',
            'onde_aplicar' => 'string|required',
            'como_aplicar' => 'string|required',
            'valor' => 'numeric|required',
            'data_de_validade' => 'date'
        ]);
            if(isset($attrs)){
                $promocao = Promocao::create([
                    'codigo' => $attrs['codigo'],
                    'descricao' => $attrs['descricao'],
                    'onde_aplicar' => $attrs['onde_aplicar'],
                    'como_aplicar' => $attrs['como_aplicar'],
                    'valor' => $attrs['valor'],
                    'data_de_validade' => $attrs['data_de_validade'],
                    'user_id' => auth()->user()->id
                ]);
                return Controller::retornarConteudo('Promoção cadastrada com sucesso!',$promocao,201);
            }else{
                return Controller::retornarConteudo('Erro ao cadastrar a promoção!',null,406);
            }
        }


    public function show($id)
    {
        $promocao = Promocao::find($id);
        if(isset($promocao)){
            return Controller::retornarConteudo(null,$promocao,200);
        }else{
            return Controller::retornarConteudo('Promoção não encontrada',null,406);
        }
    }

    public function update(Request $request, $id)
    {
        $promocao = Promocao::find($id);
        if(isset($promocao)){
            $attrs = $request->validate([
                'codigo' => 'string',
                'descricao' => 'string',
                'onde_aplicar' => 'string',
                'como_aplicar' => 'string',
                'valor' => 'numeric',
                'data_de_validade' => 'date'
            ]);
            $promocao->update([
                'codigo' => $attrs['codigo'],
                'descricao' => $attrs['descricao'],
                'onde_aplicar' => $attrs['onde_aplicar'],
                'como_aplicar' => $attrs['como_aplicar'],
                'valor' => $attrs['valor'],
                'data_de_validade' => $attrs['data_de_validade'],
                'user_id' => auth()->user()->id
            ]);
            if(isset($promocao)){
                return Controller::retornarConteudo('Promoção atualizada com sucesso',$promocao,200);
            }else{
                return Controller::retornarConteudo('Erro ao atualizar a promoção',null,500);
            }
        }else{
            return Controller::retornarConteudo('Promoção não encontrada',null,406);
        }
    }

    public function destroy($id)
    {
        $promocao = Promocao::find($id);
        if(isset($promocao)){
            if($promocao->delete()){
                return Controller::retornarConteudo('Promoção deletada com sucesso',$promocao,200);
            }else{
                return Controller::retornarConteudo('Erro ao deletar a promoção',null,500);
            }
        }else{
            return Controller::retornarConteudo('Promoção não encontrada',null,406);
        }
    }
}
