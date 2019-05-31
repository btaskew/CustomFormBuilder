<?php

namespace App\Contracts;

use App\FormResponse;

interface FormResponseMailMapper
{
    /**
     * @param string       $text
     * @param FormResponse $response
     * @return string
     */
    public function mapResponse(string $text, FormResponse $response): string;
}
