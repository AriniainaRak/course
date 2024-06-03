<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chronos extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'chronos';
    public $timestamps = false;
    public $fillable = [
        'idcoureur',
        'idetape',
        'heure_depart',
        'heure_arrive   '
    ];

    public function etape()
    {
        return $this->belongsTo(Etapes::class, 'idetape');
    }

    public function coureur()
    {
        return $this->belongsTo(Coureurs::class, 'idcoureur');
    }
}
