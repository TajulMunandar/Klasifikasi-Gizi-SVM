<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    /** @use HasFactory<\Database\Factories\ClassificationFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function dataAnak()
    {
        return $this->belongsTo(DataAnak::class, 'id_data_anak');
    }
}
