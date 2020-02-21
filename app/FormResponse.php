<?php

namespace App;

use App\Events\ResponseRecorded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormResponse extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'form_id',
        'response',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'response' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($response) {
            ResponseRecorded::dispatch($response);
        });
    }

    /**
     * @return BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
