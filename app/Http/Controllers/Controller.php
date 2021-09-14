<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected static function retornarConteudo($mensagem = null, $objeto = null, $status){
        if($objeto != null && $mensagem != null){
            return response([
                'message' => $mensagem,
                'objeto' => $objeto,
            ],$status);
        }else if($mensagem != null){
            return response([
                'message' => $mensagem,
            ],$status);
        }else if($objeto != null){
            return response([
                'objeto' => $objeto,
            ],$status);
        }else{
            return response([
                'message' => 'Erro de requisição!',
            ],402);
        }
    }
}
