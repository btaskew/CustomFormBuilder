<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

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
        'access'
    ];

    /**
     * @param string $username
     * @param string $access
     * @param int    $formId
     * @return JsonResponse
     */
    public static function createAccess(string $username, string $access, int $formId)
    {
        if ($username == auth()->user()->username) {
            return response()->json(['error' => "Can't grant access to self"], 422);
        }

        try {
            $user = User::where('username', $username)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Given user was not found in the database'], 404);
        }

        if (FormUser::where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'User already has access'], 422);
        }

        $user->pivot = self::create([
            'user_id' => $user->id,
            'form_id' => $formId,
            'access' => $access
        ]);

        return $user;
    }
}
