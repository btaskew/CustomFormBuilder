<?php

namespace App;

use App\Specifications\CanSetVisibilityRequirement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    const SELECT_QUESTION_TYPES = ['checkbox', 'radio', 'dropdown'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'help_text',
        'required',
        'admin_only',
        'order',
        'in_question_bank'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'required' => 'boolean',
        'in_question_bank' => 'boolean'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question) {
            $question->setOrder();
        });

        static::updated(function ($question) {
            if (!$question->isSelectQuestion()) {
                $question->options->each->delete();
                $question->visibilityRequirementDependants->each->delete();
            }
        });

        static::deleting(function ($question) {
            $question->options->each->delete();
            $question->visibilityRequirementDependants->each->delete();

            if ($question->visibilityRequirement()->exists()) {
                $question->visibilityRequirement->delete();
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(SelectOption::class);
    }

    /**
     * @return HasOne
     */
    public function visibilityRequirement(): HasOne
    {
        return $this->hasOne(VisibilityRequirement::class);
    }

    /**
     * Other questions which visibility depends on this
     *
     * @return HasMany
     */
    public function visibilityRequirementDependants(): HasMany
    {
        return $this->hasMany(VisibilityRequirement::class, 'required_question_id');
    }

    /**
     * @return bool
     */
    public function isSelectQuestion(): bool
    {
        return in_array($this->type, self::SELECT_QUESTION_TYPES);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = []): void
    {
        foreach ($options as $option) {
            $this->options()->updateOrCreate([
                'id' => (isset($option['id']) ? $option['id'] : null)
            ], [
                'value' => $option['value'],
                'display_value' => $option['display_value'],
            ]);
        }
    }

    /**
     * @param int    $questionId
     * @param string $questionValue
     */
    public function setVisibilityRequirement(int $questionId, string $questionValue): void
    {
        if (CanSetVisibilityRequirement::isSatisfiedBy($questionId, $questionValue, $this)) {
            $this->visibilityRequirement()->updateOrCreate([
                'question_id' => $this->id
            ], [
                'required_question_id' => $questionId,
                'required_value' => $questionValue
            ]);
        }
    }

    /**
     * Set question to be last in form if new
     */
    public function setOrder(): void
    {
        if (isset($this->order)) {
            return;
        }

        $highestOrder = $this->form->questions()->max('order');

        if (is_null($highestOrder)) {
            $this->order = 0;
        } else {
            $this->order = $highestOrder + 1;
        }
    }

    /**
     * @return string
     */
    public function getFullTitle(): string
    {
        return $this->order + 1 . '. ' . $this->title;
    }
}
