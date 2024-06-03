<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coureurs_categories extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'coureur_categories';
    public $timestamps = false;
    public $fillable = [
        'idcoureur',
        'idcategory'
    ];
}
