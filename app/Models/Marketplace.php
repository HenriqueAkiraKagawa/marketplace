<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'url', 'produto_id'];
    
    public function produto()
    {
        return $this->belongsTo(Produto::class,"produto_id");
    }
}
