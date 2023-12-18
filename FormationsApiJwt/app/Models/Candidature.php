<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;
    public function User(){
        return $this->hasMany(User::class);
    }
    public function Formation(){
        return $this->hasMany(Formation::class);
    }
}
