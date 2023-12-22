<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'formation_id',
    ];
    public function User(){
        return $this->hasMany(User::class);
    }
    public function Formation(){
        return $this->hasMany(Formation::class);
    }
}
