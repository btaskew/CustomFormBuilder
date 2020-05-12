<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'remember_token', 'password',
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
        return $this->forms->merge($this->accessibleForms)
            ->each(function (Form $form) {
                $form->editAccess = $this->hasAccessTo('edit', $form);
            });
    }

    /**
     * @param string $access
     * @param Form   $form
     * @return bool
     */
    public function hasAccessTo(string $access, Form $form): bool
    {
        return $this->id == $form->user_id || $this->hasBeenGrantedAccess($access, $form);
    }

    /**
     * @param string $access
     * @param Form   $form
     * @return bool
     */
    private function hasBeenGrantedAccess(string $access, Form $form): bool
    {
        $userAccess = FormUser::where([
            'user_id' => $this->id,
            'form_id' => $form->id,
            'access' => $access,
        ]);

        if ($access == 'view') {
            $userAccess
                ->orWhere('access', 'update')
                ->where([
                    'user_id' => $this->id,
                    'form_id' => $form->id,
                ]);
        }

        return $userAccess->exists();
    }
}
