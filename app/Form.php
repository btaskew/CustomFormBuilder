<?php

namespace App;

use App\Http\Requests\ResponseRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'user_id',
        'folder_id'
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
        return \Purify::clean($description);
    }

    /**
     * @param string $text
     * @return string
     */
    public function getSuccessTextAttribute($text)
    {
        return \Purify::clean($text);
    }

    /**
     * @param string $email
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
     * @return Collection
     */
    public function getAnswerableQuestions(): Collection
    {
        return $this->questions()
            ->where('type', '!=', 'label')
            ->orderBy('order')
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

    /**
     * @param ResponseRequest $request
     */
    public function recordResponse(ResponseRequest $request): void
    {
        $answers = [];

        $this->getAnswerableQuestions()
            ->each(function (Question $question) use ($request, &$answers) {
                $this->addResponse($question, $request, $answers);
            });

        $this->responses()->create([
            'response' => json_encode($answers)
        ]);
    }

    /**
     * @param Question        $question
     * @param ResponseRequest $request
     * @param array           $answers
     */
    private function addResponse(Question $question, ResponseRequest $request, array &$answers): void
    {
        $answers[$question->id] = $request->getQuestionsResponse($question->id);

        if ($question->type == 'date') {
            $answers[$question->id] = (Carbon::createFromTimestampMs($answers[$question->id]))->toDateString();
        }
    }
}
