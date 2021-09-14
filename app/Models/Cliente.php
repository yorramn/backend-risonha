<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ["id","nome","email","cpf","cep","logradouro","numero","cidade","telefone","user_id"];

}
