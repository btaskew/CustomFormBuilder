<?php

namespace App\Objects;

class FormattedResponse
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $timeRecorded;

    /**
     * @var array
     */
    private $answers = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FormattedResponse
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeRecorded(): string
    {
        return $this->timeRecorded;
    }

    /**
     * @param string $timeRecorded
     * @return FormattedResponse
     */
    public function setTimeRecorded(string $timeRecorded)
    {
        $this->timeRecorded = $timeRecorded;
        return $this;
    }

    /**
     * @param string $question
     * @param string $answer
     * @return FormattedResponse
     */
    public function addAnswer(string $question, string $answer = 'n/a')
    {
        $this->answers[$question] = $answer;
        return $this;
    }

    /**
     * @param string $question
     * @return FormattedResponse
     */
    public function addBlankAnswer(string $question)
    {
        $this->answers[$question] = 'n/a';
        return $this;
    }

    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }
}
