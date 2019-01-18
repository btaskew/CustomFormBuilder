<?php

namespace App\Specifications;

use App\Form;
use App\FormResponse;

class CanSendResponseEmail
{
    /**
     * @param Form         $form
     * @param FormResponse $response
     * @return bool
     */
    public static function isSatisfiedBy(Form $form, FormResponse $response): bool
    {
        if (self::formFieldsNotSet($form)) {
            return false;
        }

        if (!static::responseIncludesValidEmail($form->response_email_field, $response)) {
            return false;
        }

        return true;
    }

    /**
     * @param Form $form
     * @return bool
     */
    private static function formFieldsNotSet(Form $form): bool
    {
        return is_null($form->response_email_field) || is_null($form->response_email) || $form->response_email == '';
    }

    /**
     * @param int          $questionId
     * @param FormResponse $response
     * @return bool
     */
    private static function responseIncludesValidEmail(int $questionId, FormResponse $response): bool
    {
        $email = $response->response->{$questionId};

        if (is_null($email)) {
            return false;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}