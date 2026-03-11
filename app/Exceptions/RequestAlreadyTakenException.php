<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class RequestAlreadyTakenException extends Exception
{
    public function __construct(string $message = 'This request is already being worked on.')
    {
        parent::__construct($message);
    }
}
