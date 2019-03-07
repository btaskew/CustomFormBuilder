<?php

namespace App;

use App\Events\ResponseRecorded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormResponse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id',
        'response',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($response) {
            event(new ResponseRecorded($response));
        });
    }

    /**
     * @param $response
     * @return mixed
     */
    public function getResponseAttribute($response)
    {
        return json_decode($response);
    }

    /**
     * @return BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
