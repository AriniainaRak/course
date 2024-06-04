<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'results';
    public $timestamps = false;
    public $fillable = [
        'idetape',
        'idcoureur',
        'heure_arrive'
    ];

    public function etape()
    {
        return $this->belongsTo(Etapes::class, 'idequipe');
    }

    public function coureur()
    {
        return $this->belongsTo(Coureurs::class, 'idequipe');
    }
}
