<?php

namespace App\Http\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ProxyAuthenticationRequiredException
 * NS: App\Exceptions\Http
 */
class ProxyAuthenticationRequiredException extends HttpException
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
        parent::__construct(407, $message, $previous, array(), $code);
    }
}
