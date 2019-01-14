<?php

namespace App;

use App\Specifications\CanSetVisibilityRequirement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'help_text',
        'required' ,
        'admin_only',
        'order'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['options', 'visibilityRequirement'];

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
        return $this->type == 'checkbox' || $this->type == 'radio' || $this->type == 'dropdown';
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
     * @param array $requirement
     */
    public function setVisibilityRequirement(array $requirement): void
    {
        if (CanSetVisibilityRequirement::isSatisfiedBy($requirement)) {
            $this->visibilityRequirement()->updateOrCreate([
                'question_id' => $this->id
            ], [
                'required_question_id' => $requirement['question'],
                'required_value' => $requirement['value']
            ]);
        }
    }

    /**
     * Set question to be last in form
     */
    private function setOrder(): void
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
}
