<?php

namespace App\Http\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PreconditionFailedException
 * NS: App\Exceptions\Http
 */
class PreconditionFailedException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(412, $message, $previous, array(), $code);
    }
}
