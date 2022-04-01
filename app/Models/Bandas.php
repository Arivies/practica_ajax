<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bandas extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'genero_id', 'logo'];


    public function genero()
    {
        return $this->belongsTo(Generos::class);
    }
}
