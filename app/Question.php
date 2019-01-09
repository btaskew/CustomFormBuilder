<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    protected $with = ['options'];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question) {
            /** @var Question $question */
            $question->setOrder();
        });

        static::deleting(function ($question) {
            /** @var Question $question */
            $question->options->each->delete();
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
     * @return bool
     */
    public function isSelectQuestion(): bool
    {
        return $this->type == 'checkbox' || $this->type == 'radio' || $this->type == 'dropdown';
    }

    /**
     * @param array $options
     */
    public function addOptions(array $options = []): void
    {
        foreach ($options as $option) {
            $this->options()->create([
                'value' => $option['value'],
                'display_value' => $option['display_value'],
            ]);
        }
    }

    /**
     * @param array $options
     */
    public function updateOptions(array $options = []): void
    {
        foreach ($options as $option) {
            $this->options()->updateOrCreate([
                'id' => $option['id']
            ], [
                'value' => $option['value'],
                'display_value' => $option['display_value'],
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
