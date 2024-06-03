<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coureurs extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'coureurs';
    public $timestamps = false;
    public $fillable = [
        'name',
        'dossard_number',
        'gender',
        'birth_date',
        'idequipe'
    ];

    public function equipe()
    {
        return $this->belongsTo(Equipes::class, 'idequipe');
    }

    public function etape_assignment()
    {
        return $this->hasManyThrough(Etape_assignments::class, Coureurs::class);
    }

    public function etapes()
    {
        return $this->belongsToMany(Etapes::class, 'etape_assignments', 'idcoureur', 'idetape');
    }
}
