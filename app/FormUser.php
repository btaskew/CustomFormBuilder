<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'form_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'form_id'
    ];
}
