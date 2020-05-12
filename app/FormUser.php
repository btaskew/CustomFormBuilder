<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'form_user';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'form_id',
        'access',
    ];

    /**
     * @param string $username
     * @param string $access
     * @param int    $formId
     * @return User
     */
    public static function createAccess(string $username, string $access, int $formId)
    {
        $user = User::where('username', $username)->firstOrFail();

        $user->pivot = self::updateOrCreate([
            'user_id' => $user->id,
            'form_id' => $formId,
        ], [
            'user_id' => $user->id,
            'form_id' => $formId,
            'access' => $access,
        ]);

        return $user;
    }
}
