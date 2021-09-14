<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    use HasFactory;
    protected $fillable = ['id','codigo','descricao','onde_aplicar','como_aplicar','valor','data_de_validade','user_id'];
    public function user(){
        $this->belongsTo('App\Models\User');
    }
}
