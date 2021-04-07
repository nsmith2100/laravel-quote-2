<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotePerson extends Model
{
    use HasFactory;
    protected $fillable = ['quote_id', 'age'];
}
