<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];


    /**
     * @param Exception $exception
     * @return Response
     */
    protected function convertExceptionToResponse(Exception $exception): Response
    {
        $exception = FlattenException::create($exception);

        return response()->error($exception->getStatusCode(), $exception->getMessage());
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request)
    {
        if ($request->expectsJson()) {
            return response()->error(Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('loginForm'));
    }

    /**
     * Render an exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
            return response()->json(trans($e->getMessage()), $e->getStatusCode());
        } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json(trans($e->getMessage()), $e->getStatusCode());
        } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return response()->json(trans($e->getMessage()), $e->getStatusCode());
        }

        return parent::render($request, $e);
    }
}
