<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape_assignments extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'etape_assignments';
    public $timestamps = false;
    public $fillable = [
        'idetape',
        'idcoureur'
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
