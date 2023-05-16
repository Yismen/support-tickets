<?php

namespace Dainsys\Support\Exceptions;

use Exception;

class DifferentDepartmentException extends Exception
{
    protected $message = 'Assigning tickets to agents from other departments is not allowed!';
    protected $code = 403;
}
