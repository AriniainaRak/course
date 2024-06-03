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
        'birth_date'
    ];

    public function equipe()
    {
        return $this->hasMany(Equipes::class);
    }

    public function etape_assignment()
    {
        return $this->hasManyThrough(Etape_assignments::class, Coureurs::class);
    }
}
