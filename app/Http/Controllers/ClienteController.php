<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $cliente = Cliente::all();
        if(count($cliente) > 0){
            return Controller::retornarConteudo(null,$cliente,200);
        }else{
            return Controller::retornarConteudo('Não há clientes cadastrados',null,200);
        }
    }


    public function store(Request $request)
    {
        $attrs = $request->validate([
            'nome' => 'string|required',
            'cpf' => 'string|required',
            'email' => 'email|required',
            'cep' => 'string|required',
            'logradouro' => 'string|required',
            'numero' => 'integer|required',
            'cidade' => 'string|required',
            'telefone' => 'integer|required',
        ]);

        $cliente = Cliente::create([
            'nome' => $attrs['nome'],
            'cpf' => $attrs['cpf'],
            'email' => $attrs['email'],
            'cep' => $attrs['cep'],
            'logradouro' => $attrs['logradouro'],
            'numero' => $attrs['numero'],
            'cidade' => $attrs['cidade'],
            'telefone' => $attrs['telefone'],
            'user_id' => auth()->user()->id
        ]);
        if(isset($cliente)){
            return Controller::retornarConteudo('Cliente cadastrado com sucesso!',$cliente,201);
        }else{
            return Controller::retornarConteudo('Erro ao cadastrar usuário!',null,406);
        }

    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        if($cliente){
            return Controller::retornarConteudo(null,$cliente,200);
        }else{
            return Controller::retornarConteudo('Não há ninguém com este cpf cadastrado',null,200);
        }
    }
    public function showXml($id)
    {
        $data = Cliente::find($id);
        if($data){
            $response = null;
                $response .= "<cliente>";
                $response .= "<id>".$data->id."</id>";
                $response .= "<nome>".$data->nome."</nome>";
                $response .= "<email>".$data->email."</email>";
                $response .= "<cpf>".$data->cpf."</cpf>";
                $response .= "<cep>".$data->cep."</cep>";
                $response .= "<logradouro>".$data->logradouro."</logradouro>";
                $response .= "<numero>".$data->numero."</numero>";
                $response .= "<cidade>".$data->cidade."</cidade>";
                $response .= "<telefone>".$data->telefone."</telefone>";
                $response .= "</cliente>";
            return response($response)->header('Content-Type','application/xml');
        }else{
            return Controller::retornarConteudo('Não há ninguém com este cpf cadastrado',null,200);
        }
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if(isset($cliente)){
            $res = $cliente->update($request->all());
            if($res){
                return Controller::retornarConteudo('Dados de '.$request->nome.' atualizados com sucesso!',$cliente,200);
            }
        }else{
            return Controller::retornarConteudo('Erro ao atualizar os dados de '.$cliente->nome,null,406);
        }
    }

    public function atualizar(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if(isset($cliente)){
            $res = $cliente->update($request->all());
            if($res){
                return Controller::retornarConteudo('Dados de '.$request->nome.' atualizados com sucesso!',$cliente,200);
            }
        }else{
            return Controller::retornarConteudo('Erro ao atualizar os dados de '.$cliente->nome,null,406);
        }
    }
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if(isset($cliente)){
            if($cliente->delete()){
                return Controller::retornarConteudo('Dados de '.$cliente->nome.' excluídos com sucesso!',null,200);
            }else{
                return Controller::retornarConteudo('Erro ao excluir dados de '.$cliente->nome.' excluídos com sucesso!',null,406);
            }
        }else{
            return Controller::retornarConteudo('Erro ao excluir dados!',null,500);
        }
    }
}
