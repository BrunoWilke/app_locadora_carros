<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules(){
        return 
        [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg'
        ];
    }
    public function feedback(){
        return 
        [
            'required' => 'O campo :attribute deve ser preenchido',
            'nome.unique' => 'O nome preenchido ja exite no banco',
            'nome.min' => 'O nome deve ter no minimo 3 letras',
            'imagem.mimes' => 'O arquivo inserido nao corresponde ao tipo png pu jpeg'
        ];
    }
}
