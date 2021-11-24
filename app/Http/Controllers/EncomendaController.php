<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Encomenda;
use App\Models\Produto;
use App\Models\Promocao;
use Illuminate\Http\Request;

class EncomendaController extends Controller
{
    private function validaCliente($cliente, $promocao)
    {
        $status = false;
        if (count(Encomenda::all()) > 0) {
            foreach (encomenda::all() as $encomenda) {
                if ($encomenda->cliente_id == $cliente->id && $encomenda->promocao_id == $promocao->id) {
                    $status = false;
                } else if ($encomenda->cliente_id == $cliente->id && $encomenda->promocao_id != $promocao->id) {
                    $status = true;
                } else if ($encomenda->cliente_id != $cliente->id) {
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


    public function index()
    {
        if (count(Encomenda::all()) > 0) {
            if ($nota_fiscal != null) {
                $encomendas = Encomenda::where([
                    ['nota_fiscal','LIKE','%'.$nota_fiscal.'%']
                ])->get();
                return Controller::retornarConteudo(null, $encomendas, 200);
            } else {
                return Controller::retornarConteudo(null, Encomenda::all(), 200);
            }
        } else {
            return Controller::retornarConteudo('Não existem encomendas cadastradas', null, 200);
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
                    if ($produto->quantidade <= $value[$key]) {
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
        $promocao = Promocao::
        where('codigo', $request->codigo_promocao)
        ->get()->first();
        $cliente = Cliente::
        where('cpf', $request->cpf_cliente)
        ->get()->first();
        $attrs = $request->validate([
            'codigos' => 'array|required',
            'nomes' => 'array|required',
            'precos' => 'array|required',
            'quantidade_itens' => 'array|required',
            'total' => 'numeric|required',
            'data_de_pagamento' => 'date|required',
            'data_de_recebimento' => 'date|required'
        ]);
        if (!$this->subProduct($attrs)) {
            return Controller::
            retornarConteudo(
                'Erro! Produto com estoque menor ou igual à quantidade requisitada'
                , null, 406);
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
                        $encomenda = Encomenda::create([
                            'codigos' => $attrs['codigos'],
                            'nomes' => $attrs['nomes'],
                            'precos' => $attrs['precos'],
                            'quantidade_itens' => $attrs['quantidade_itens'],
                            'total' => $result,
                            'nota_fiscal' => rand(000000001, 999999999),
                            'data_de_recebimento' => $attrs['data_de_recebimento'],
                            'data_de_pagamento' => $attrs['data_de_recebimento'],
                            'user_id' => auth()->user()->id,
                            'promocao_id' => $promocao->id,
                            'cliente_id' => $cliente->id
                        ]);
                        if ($encomenda) {
                            return Controller::retornarConteudo('Encomenda realizada com sucesso', $encomenda, 200);
                        } else {
                            return Controller::retornarConteudo('Erro na efetuação da encomenda', $encomenda, 500);
                        }
                    } else {
                        return Controller::retornarConteudo('Promoção não pode ser aplicada ao mesmo cliente', null, 406);
                    }
                }
            } else if ($promocao != null) {
                $result = $this->verificaValorAplicacao($promocao->como_aplicar, $attrs['total'], $promocao->valor);
                $encomenda = Encomenda::create([
                    'codigos' => $attrs['codigos'],
                    'nomes' => $attrs['nomes'],
                    'precos' => $attrs['precos'],
                    'quantidade_itens' => $attrs['quantidade_itens'],
                    'total' => $result,
                    'nota_fiscal' => rand(000000001, 999999999),
                    'data_de_recebimento' => $attrs['data_de_recebimento'],
                    'data_de_pagamento' => $attrs['data_de_recebimento'],
                    'user_id' => auth()->user()->id,
                    'promocao_id' => $promocao->id,
                ]);
                if ($encomenda) {
                    return Controller::retornarConteudo('Encomenda realizada com sucesso', $encomenda, 200);
                } else {
                    return Controller::retornarConteudo('Erro na efetuação da encomenda', $encomenda, 500);
                }
            } else if ($cliente != null) {
                $encomenda = Encomenda::create([
                    'codigos' => $attrs['codigos'],
                    'nomes' => $attrs['nomes'],
                    'precos' => $attrs['precos'],
                    'quantidade_itens' => $attrs['quantidade_itens'],
                    'total' => $attrs['total'],
                    'nota_fiscal' => rand(000000001, 999999999),
                    'data_de_recebimento' => $attrs['data_de_recebimento'],
                    'data_de_pagamento' => $attrs['data_de_recebimento'],
                    'user_id' => auth()->user()->id,
                    'cliente_id' => $cliente->id
                ]);
                if ($encomenda) {
                    return Controller::retornarConteudo('Encomenda realizada com sucesso', $encomenda, 200);
                } else {
                    return Controller::retornarConteudo('Erro na efetuação da encomenda', $encomenda, 500);
                }
            } else {
                $encomenda = Encomenda::create([
                    'codigos' => $attrs['codigos'],
                    'nomes' => $attrs['nomes'],
                    'precos' => $attrs['precos'],
                    'quantidade_itens' => $attrs['quantidade_itens'],
                    'total' => $attrs['total'],
                    'nota_fiscal' => rand(000000001, 999999999),
                    'data_de_recebimento' => $attrs['data_de_recebimento'],
                    'data_de_pagamento' => $attrs['data_de_recebimento'],
                    'user_id' => auth()->user()->id,
                ]);
                if ($encomenda) {
                    return Controller::retornarConteudo('Encomenda realizada com sucesso', $encomenda, 200);
                } else {
                    return Controller::retornarConteudo('Erro na efetuação da encomenda', $encomenda, 500);
                }
            }
        }
    }

    public function show($id)
    {
        $encomenda = Encomenda::find($id);
        if (isset($encomenda)) {
            return Controller::retornarConteudo(null, $encomenda, 200);
        } else {
            return Controller::retornarConteudo("Não foi possível localizar a encomenda", null, 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
