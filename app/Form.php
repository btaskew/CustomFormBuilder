<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Form extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'active',
        'open_date',
        'close_date',
        'admin_email',
        'success_text',
        'response_email',
        'response_email_field',
        'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($form) {
            $form->questions->each->delete();
        });
    }

    /**
     * @param  string $description
     * @return string
     */
    public function getDescriptionAttribute($description)
    {
        return \Purify::clean($description);
    }

    /**
     * @param  string $text
     * @return string
     */
    public function getSuccessTextAttribute($text)
    {
        return \Purify::clean($text);
    }

    /**
     * @param  string $email
     * @return string
     */
    public function getResponseEmailAttribute($email)
    {
        return \Purify::clean($email);
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * @return HasMany
     */
    public function responses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }

    /**
     * @return Collection
     */
    public function getOrderedQuestions(): Collection
    {
        return $this->questions()
            ->orderBy('order')
            ->with(['options', 'visibilityRequirement'])
            ->get();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $currentDate = new Carbon();
        return $this->active && $currentDate->between((new Carbon($this->open_date)), (new Carbon($this->close_date)));
    }
}
