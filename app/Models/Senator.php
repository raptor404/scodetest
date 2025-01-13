<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Senator extends Model
{
    protected $table = 'senator';

    protected  $primaryKey= 'id';
    protected $fillable = [
        'first_name',
        'affiliation',
        'email',
    ];
}
