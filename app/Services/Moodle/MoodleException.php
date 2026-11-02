<?php

namespace App\Services\Moodle;

use RuntimeException;

class MoodleException extends RuntimeException
{
    public function __construct(string $message, public ?string $function = null, public array $payload = [])
    {
        parent::__construct($message);
    }
}
