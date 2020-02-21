<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisibilityRequirement extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'question_id',
        'required_question_id',
        'required_value',
    ];
}
