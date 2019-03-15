<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ResponseFormatter
{
    /**
     * @param Collection $questions
     * @return ResponseFormatter
     */
    public function setQuestions(Collection $questions): ResponseFormatter;

    /**
     * @param array $responses
     * @return array
     */
    public function formatResponses(array $responses): array;
}
