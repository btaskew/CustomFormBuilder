<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisibilityRequirement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id',
        'required_question_id',
        'required_value',
    ];
}
