<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAnak extends Model
{
    /** @use HasFactory<\Database\Factories\DataAnakFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function Classification()
    {
        return $this->hasMany(Classification::class, 'id');
    }
}
