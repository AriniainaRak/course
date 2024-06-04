<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genres extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'genres';
    public $timestamps = false;
    public $fillable = [
        'name'
    ];
}
