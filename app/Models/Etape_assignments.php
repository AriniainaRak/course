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
}
