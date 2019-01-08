<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelectOption extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'display_value',
    ];
}
