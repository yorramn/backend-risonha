<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    use HasFactory;
    protected $casts = [
        'codigos' => 'array',
        'nomes' => 'array',
        'precos' => 'array',
        'quantidade_itens' => 'array'
    ];

    protected $fillable = ['id','codigos','nomes','precos','quantidade_itens','total','nota_fiscal','user_id','data_de_pagamento','data_de_recebimento','promocao_id','cliente_id'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function promocao(){
        return $this->hasOne('App\Models\Promocao');
    }
    public function cliente(){
        return $this->belongsTo('App\Models\Cliente');
    }
}
