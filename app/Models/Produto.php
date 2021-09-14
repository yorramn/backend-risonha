<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $fillable = ['id','codigo','nome','quantidade','data_de_validade','tipo_de_quantidade','peso','tipo_de_peso','fabricante','preco','user_id','categoria_id'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function categoria(){
        return $this->belongsTo('App\Models\Categoria');
    }
}
