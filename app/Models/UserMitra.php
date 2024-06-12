<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserMitra extends Model
{
    use HasFactory;

    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    public function isMitra()
    {
        return $this->role === 'mitra';
    }
}
