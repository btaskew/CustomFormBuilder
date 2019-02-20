<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return HasMany
     */
    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    /**
     * @return BelongsToMany
     */
    public function accessibleForms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class);
    }

    /**
     * @return Collection
     */
    public function getAllForms(): Collection
    {
        return $this->forms->merge($this->accessibleForms);
    }

    /**
     * @param Form $form
     * @return bool
     */
    public function hasAccessTo(Form $form): bool
    {
        return (
            $this->id == $form->user_id || FormUser::where(['user_id' => $this->id, 'form_id' => $form->id])->exists()
        );
    }
}
