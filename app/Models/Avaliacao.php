<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $fillable = ['descricao', 'nota', 'produto_id'];

    public function produto()
    {
        return $this->belongsTo(Produto::class,'produto_id');
    }
}
