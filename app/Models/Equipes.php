<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipes extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'equipes';
    public $timestamps = false;
    public $fillable = [
        'name',
        'username',
        'password'
    ];

    public function coureur()
    {
        return $this->hasMany(Coureurs::class);
    }

    public function etape_assignment()
    {
        return $this->hasManyThrough(Etape_assignments::class, Coureurs::class);
    }

    // Schema::table('equipes', function (Blueprint $table) {
    // $table->string('access_token')->unique()->nullable();
    // });

}
