<?php

namespace App;

use App\Mappers\FormMapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kris\LaravelFormBuilder\Facades\FormBuilder;

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
     * @return bool
     */
    public function isActive(): bool
    {
        $currentDate = new Carbon();
        return $this->active && $currentDate->between((new Carbon($this->open_date)), (new Carbon($this->close_date)));
    }

    /**
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function build(): \Kris\LaravelFormBuilder\Form
    {
        $formView = FormBuilder::plain(['name' => $this->title]);
        return (new FormMapper($formView))->mapQuestions($this->questions()->orderBy('order')->get());
    }

    /**
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function buildPreview(): \Kris\LaravelFormBuilder\Form
    {
        $formView = FormBuilder::plain(['name' => $this->title]);
        return (new FormMapper($formView))->mapQuestions($this->questions()->orderBy('order')->get());
    }
}
