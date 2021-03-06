<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{

    //método de retorno
    private function retorno($mensagem, $status, $objeto = null)
    {
        return response([
            'mensagem' => $mensagem,
            'objeto' => $objeto
        ], $status);
    }

    public function index(string $nome = null)
    {
        if (count(Categoria::all()) > 0) {
            if ($nome != null) {
                $catagorias = Categoria::where([
                    ['nome','LIKE','%'.$nome.'%']
                ])->get();
                return $this->retorno(null, 200, $catagorias);
            } else {
                return $this->retorno(null, 200,Categoria::all());
            }
        } else {
            return $this->retorno('Não há categorias cadastradas', 200, null);
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
        if (isset($categoria)) {
            return $this->retorno('Categoria cadastrada com sucesso', 201, $categoria);
        } else {
            return $this->retorno('Erro ao cadastrar a categoria', 500);
        }
    }

    public function show($id)
    {
        $categoria = Categoria::find($id);
        if (isset($categoria)) {
            return Controller::retornarConteudo(null, $categoria, 200);
        } else {
            return Controller::retornarConteudo('Categoria não encontrada!', null, 402);
        }
    }
    public function showXml($id)
    {
        $data = Categoria::find($id);
        if ($data) {
            $response = null;
            $response .= "<categoria>";
            $response .= "<id>" . $data->id . "</id>";
            $response .= "<nome>" . $data->nome . "</nome>";
            $response .= "<descricao>" . $data->descricao . "</descricao>";
            $response .= "</categoria>";
            return response($response)->header('Content-Type', 'application/xml');
        } else {
            return Controller::retornarConteudo('Categoria não encontrada!', null, 200);
        }
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        if (isset($categoria)) {
            $attrs = $request->validate([
                'nome' => 'string',
                'descricao' => 'string'
            ]);
            $categoria->update([
                'nome' => $attrs['nome'],
                'descricao' => $attrs['descricao'],
                'user_id' => auth()->user()->id
            ]);
            return Controller::retornarConteudo('Categoria editada com sucesso', $categoria, 200);
        } else {
            return Controller::retornarConteudo('Categoria não encontrada', null, 402);
        }
    }

    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        if (isset($categoria)) {
            try {
                $categoria->delete();
                return Controller::retornarConteudo('Categoria deletada com sucesso', $categoria, 200);
            } catch (Exception $e) {
                return Controller::retornarConteudo('Categoria não pôde ser removida pois está atrelada a algum produto!', null, 200);
            }
        } else {
            return Controller::retornarConteudo('Categoria não encontrada', null, 402);
        }
    }
}
