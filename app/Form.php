<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Stevebauman\Purify\Facades\Purify;

class Form extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'active',
        'max_responses',
        'open_date',
        'close_date',
        'admin_email',
        'success_text',
        'response_email',
        'response_email_field',
        'user_id',
        'folder_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($form) {
            $form->questions->each->delete();
            $form->usersWithAccess->each(function (User $user) {
                $user->pivot->delete();
            });
        });
    }

    /**
     * @param string $description
     * @return string
     */
    public function getDescriptionAttribute($description)
    {
        return Purify::clean($description);
    }

    /**
     * @param string $text
     * @return string
     */
    public function getSuccessTextAttribute($text)
    {
        return Purify::clean($text);
    }

    /**
     * @param string $email
     * @return string
     */
    public function getResponseEmailAttribute($email)
    {
        return Purify::clean($email);
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
     * @return BelongsTo
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * @return BelongsToMany
     */
    public function usersWithAccess(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('id', 'access', 'created_at');
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
     * @param array $columns
     * @return Collection
     */
    public function getAnswerableQuestions(array $columns = ['*']): Collection
    {
        return $this->questions()
            ->where('type', '!=', 'label')
            ->orderBy('order')
            ->get($columns);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active
            && $this->isBetweenActiveDates()
            && $this->hasNotReachMaxResponses();
    }

    /**
     * @return bool
     */
    private function isBetweenActiveDates(): bool
    {
        return (new Carbon())->between(new Carbon($this->open_date), new Carbon($this->close_date));
    }

    /**
     * @return bool
     */
    private function hasNotReachMaxResponses(): bool
    {
        return is_null($this->max_responses) || $this->responses()->count() < $this->max_responses;
    }
}
