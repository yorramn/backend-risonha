<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['id','nome','descricao','user_id',];

    public function produtos(){
        return $this->hasMany('App\Models\Produto');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
