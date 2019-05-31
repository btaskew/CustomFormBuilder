<?php

namespace App\Services;

use App\Contracts\FormResponseMailMapper as FormResponseMailMapperContract;
use App\FormResponse;

class FormResponseMailMapper implements FormResponseMailMapperContract
{
    /**
     * @param string       $text
     * @param FormResponse $response
     * @return string
     */
    public function mapResponse(string $text, FormResponse $response): string
    {
        return preg_replace_callback('/\[(\d+)\]/', function ($match) use ($response) {
            return $this->replaceKeyWithResponse($match, $response);
        }, $text);
    }

    /**
     * @param array        $keys
     * @param FormResponse $response
     * @return string
     */
    private function replaceKeyWithResponse(array $keys, FormResponse $response): string
    {
        if (array_key_exists($keys[1], $response->response)) {
            return $response->response[$keys[1]];
        }

        return '';
    }
}
