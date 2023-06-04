<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\models\user;
class Program extends Model
{
    use HasFactory;
    protected $fillable = [
      "name",
          "image",
          "coach",
          "number_of_players",
          "price",
          "description"
      ];
      
      public function users()
    {
        return $this->belongsToMany(User::class, 'program_user', 'program_id', 'user_id');
    }
}
