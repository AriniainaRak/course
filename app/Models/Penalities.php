<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalities extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'penalities';
    public $timestamps = false;
    public $fillable = [
        'idequipe',
        'idetape',
        'penalty'
    ];

    public function equipe()
    {
        return $this->belongsTo(Equipes::class, 'idequipe');
    }

    public function etape()
    {
        return $this->belongsTo(Etapes::class, 'idetape');
    }
}
