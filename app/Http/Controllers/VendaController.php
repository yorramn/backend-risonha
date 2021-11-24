<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Promocao;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    private function validaCliente($cliente, $promocao)
    {
        $status = false;
        if (count(Venda::all()) > 0) {
            foreach (Venda::all() as $venda) {
                if ($venda->cliente_id == $cliente->id && $venda->promocao_id == $promocao->id) {
                    $status = false;
                } else if ($venda->cliente_id == $cliente->id && $venda->promocao_id != $promocao->id) {
                    $status = true;
                } else if ($venda->cliente_id != $cliente->id) {
                    $status = true;
                }
            }
        } else {
            return true;
        }

        return $status;
    }
    private function verificaValorAplicacao($regra, $total, $valor)
    {
        $resultado = 0;
        if ($regra == "valor_bruto") {
            $resultado = $total - $valor;
        } else if ($regra == "porcentagem") {
            $resultado = $total - ($total * ($valor / 100));
        }
        return $resultado;
    }


    public function index(string $nota_fiscal = null)
    {
        if (count(Venda::all()) > 0) {
            if ($nota_fiscal != null) {
                $vendas = Venda::where([
                    ['nota_fiscal','LIKE','%'.$nota_fiscal.'%']
                ])->get();
                return $this->retorno(null, 200, $vendas);
            } else {
                return $this->retorno(null, 200,Venda::all());
            }
        } else {
            return $this->retorno('Não há vendas cadastradas', 200, null);
        }
    }
    private function subProduct(array $values)
    {
        $produtos = [];
        $response = null;
        foreach ($values as $key => $value) {
            if ($key == "codigos") {
                foreach ($value as $codigo) {
                    array_push($produtos, Produto::where('codigo', $codigo)->get()->first());
                }
            }
            if ($key == "quantidade_itens") {
                foreach ($produtos as $key => $produto) {
                    if ($produto->quantidade < $value[$key]) {
                        $response = false;
                    } else {
                        $produto->quantidade = $produto->quantidade - $value[$key];
                        $response = $produto->save();
                    }
                }
            }
        }
        return $response;
    }

    public function store(Request $request)
    {
        //Variáveis
        $promocao = Promocao::where('codigo', $request->codigo_promocao)->get()->first();
        $cliente = Cliente::where('cpf', $request->cpf_cliente)->get()->first();
        $attrs = $request->validate([
            'codigos' => 'array|required',
            'nomes' => 'array|required',
            'precos' => 'array|required',
            'quantidade_itens' => 'array|required',
            'total' => 'numeric|required',
        ]);
        if (!$this->subProduct($attrs)) {
            return Controller::retornarConteudo('Erro! Produto com estoque menor ou igual à quantidade requisitada', null, 406);
        } else {
            if ($promocao != null && $cliente != null) {
                if (!isset($promocao)) {
                    return Controller::retornarConteudo('Promoção não encontrada', null, 406);
                } else if (!isset($cliente)) {
                    return Controller::retornarConteudo('Cliente não encontrado', null, 406);
                } else if (!isset($promocao) && !isset($cliente)) {
                    return Controller::retornarConteudo('Promoção e cliente não foram encontrados', null, 406);
                } else {
                    if ($this->validaCliente($cliente, $promocao)) {
                        $result = $this->verificaValorAplicacao($promocao->como_aplicar, $attrs['total'], $promocao->valor);
                        $this->subProduct($attrs);
                        $venda = Venda::create([
                            'codigos' => $attrs['codigos'],
                            'nomes' => $attrs['nomes'],
                            'precos' => $attrs['precos'],
                            'quantidade_itens' => $attrs['quantidade_itens'],
                            'total' => $result,
                            'nota_fiscal' => rand(000000001, 999999999),
                            'user_id' => auth()->user()->id,
                            'promocao_id' => $promocao->id,
                            'cliente_id' => $cliente->id
                        ]);
                        if ($venda) {
                            return Controller::retornarConteudo('Venda realizada com sucesso', $venda, 200);
                        } else {
                            return Controller::retornarConteudo('Erro na efetuação da venda', $venda, 500);
                        }
                    } else {
                        return Controller::retornarConteudo('Promoção não pode ser aplicada ao mesmo cliente', null, 406);
                    }
                }
            } else if ($promocao != null) {
                $result = $this->verificaValorAplicacao($promocao->como_aplicar, $attrs['total'], $promocao->valor);
                $this->subProduct($attrs);
                $venda = Venda::create([
                    'codigos' => $attrs['codigos'],
                    'nomes' => $attrs['nomes'],
                    'precos' => $attrs['precos'],
                    'quantidade_itens' => $attrs['quantidade_itens'],
                    'total' => $result,
                    'nota_fiscal' => rand(000000001, 999999999),
                    'user_id' => auth()->user()->id,
                    'promocao_id' => $promocao->id,
                ]);
                if ($venda) {
                    return Controller::retornarConteudo('Venda realizada com sucesso', $venda, 200);
                } else {
                    return Controller::retornarConteudo('Erro na efetuação da venda', $venda, 500);
                }
            } else if ($cliente != null) {
                $this->subProduct($attrs);
                $venda = Venda::create([
                    'codigos' => $attrs['codigos'],
                    'nomes' => $attrs['nomes'],
                    'precos' => $attrs['precos'],
                    'quantidade_itens' => $attrs['quantidade_itens'],
                    'total' => $attrs['total'],
                    'nota_fiscal' => rand(000000001, 999999999),
                    'user_id' => auth()->user()->id,
                    'cliente_id' => $cliente->id
                ]);
                if ($venda) {
                    return Controller::retornarConteudo('Venda realizada com sucesso', $venda, 200);
                } else {
                    return Controller::retornarConteudo('Erro na efetuação da venda', $venda, 500);
                }
            } else {
                $this->subProduct($attrs);
                $venda = Venda::create([
                    'codigos' => $attrs['codigos'],
                    'nomes' => $attrs['nomes'],
                    'precos' => $attrs['precos'],
                    'quantidade_itens' => $attrs['quantidade_itens'],
                    'total' => $attrs['total'],
                    'nota_fiscal' => rand(000000001, 999999999),
                    'user_id' => auth()->user()->id,
                ]);
                if ($venda) {
                    return Controller::retornarConteudo('Venda realizada com sucesso', $venda, 200);
                } else {
                    return Controller::retornarConteudo('Erro na efetuação da venda', $venda, 500);
                }
            }
        }
    }

    public function show($id)
    {
        $venda = Venda::find($id);
        if ($venda) {
            return Controller::retornarConteudo(null, $venda, 200);
        } else {
            return Controller::retornarConteudo('Não foi possível encontrar esta venda', null, 200);
        }
    }


    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
