<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapes extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'etapes';
    public $timestamps = false;
    public $fillable = [
        'name',
        'longueur',
        'coureur_per_equipe',
        'rang',
        'datedepart',
        'heure_depart'
    ];

    public function coureur()
    {
        return $this->belongsToMany(Coureurs::class, 'results', 'idetape', 'idcoureur');
    }
}
