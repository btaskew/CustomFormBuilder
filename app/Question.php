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
}
