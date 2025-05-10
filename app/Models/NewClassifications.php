<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewClassifications extends Model
{
    /** @use HasFactory<\Database\Factories\NewClassificationsFactory> */
    use HasFactory;
    protected $guarded = ['id'];
}
