<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Operator;
use App\Services\Auth\Otp\Exceptions\OtpException;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LoginController extends AuthController
{
    /**
     * @return Response
     */
    public function getLogin()
    {
        if ($this->auth->user()) {
            request()->session()->reflash();
            return \response()->redirectTo(route('statistics'));
        }

        return \response()->render('auth.login', [
            'fields' => [
                'email'    => null,
                'password' => null,
            ],
        ]);
    }

    /**
     * @return Response
     */
    public function getLoginOperator()
    {
        return $this->auth->user()
            ? \response()->redirectTo(route('home'))
            : \response()->render('auth.loginOperator', [
                'fields' => [
                    'alias' => null,
                    'login' => null,
                    'pin'   => null,
                ],
            ]);
    }

    /**
     * @param OtpAuth $otpAuth
     * @param string  $phone
     *
     * @return Response
     */
    public function getOtpCode(OtpAuth $otpAuth, string $phone): Response
    {
        $user = $this->userRepository->findByPhone($phone);

        if (null === $user) {
            return \response()->error(Response::HTTP_NOT_FOUND, 'User with phone ' . $phone . ' not found.');
        }

        $key      = $this->throttleKey(request());
        $attempts = $this->limiter()->attempts($key);

        logger()->debug(
            sprintf(
                '[SMS] Phone - %1$s. URI - %2$s. Key - %3$s. Attempts - %4$d',
                $phone, request()->url(), $key, $attempts
            )
        );

        if ($this->hasTooManyLoginAttempts(\request())) {
            return $this->sendLockoutResponse(\request());
        }

        try {
            /** @var OtpAuth $otpAuth */
            $otpAuth->generateCode($user->phone);
        } catch (OtpException $exception) {
            $message = sprintf('[SMS][Login][Error] %1$s. Phone: %2$s', $exception->getMessage(), $user->phone);
            logger()->critical($message);

            return \response()->error(Response::HTTP_FORBIDDEN, $exception->getMessage());
        }

        $this->incrementLoginAttempts(\request());

        return \response()->render('auth.sms.success', ['phone_number' => $user->phone, 'code' => null],
            Response::HTTP_ACCEPTED, route('register'));
    }

    /**
     * @return Response
     */
    public function logout()
    {
        if (false === $this->jwtAuth->getToken() && !$this->auth->guard()->user() instanceof Operator) {
            $this->user()->leaveImpersonation();
        }

        $route = $this->auth->guard()->user() instanceof Operator ? 'loginFormOperator' : 'login';
        $this->auth->guard()->logout();

        return \request()->wantsJson()
            ? \response()->render('', '', Response::HTTP_NO_CONTENT)
            : \redirect()->route($route);
    }

    /**
     * @return Response
     */
    public function tokenRefresh()
    {
        try {
            $token = $this->jwtAuth->refresh();
        } catch (TokenInvalidException $e) {
            return \response()->error(Response::HTTP_UNAUTHORIZED, 'The token is invalid');
        }

        return \response()->json(compact('token'));
    }

    /**
     * @param LoginRequest $request
     * @param Session      $session
     *
     * @return Response
     * @throws \LogicException
     */
    public function login(LoginRequest $request, Session $session)
    {
        $user            = null;
        $defaultProvider = 'users';

        $credentials = $request->credentials();

        foreach (\config('auth.guards') as $guardName => $config) {
            try {
                $validated = $this->auth->guard($guardName)->validate($credentials);
            } catch (QueryException $queryException) {
                $validated = false;
            }

            if (false === $validated) {
                continue;
            }

            $providerName = $config['provider'] ?? $defaultProvider;
            $provider     = $this->auth->createUserProvider($providerName);
            $user         = $provider->retrieveByCredentials($credentials);

            break;
        }

        if (null === $user) {
            return $this->sendFailedLoginResponse($request);
        }

        event(new Login($user, false));

        $session->migrate(true);

        return $request->wantsJson()
            ? $this->postLoginJwt($user)
            : $this->postLoginSession($user);
    }

    /**
     * @param Authenticatable $user
     *
     * @return Response
     * @throws \LogicException
     */
    private function postLoginJwt(Authenticatable $user): Response
    {
        $token = $this->jwtAuth->fromUser($user);

        return \response()->render('', \compact('token'));
    }

    /**
     * @param Authenticatable $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \LogicException
     */
    private function postLoginSession(Authenticatable $user)
    {
        $guardName = 'web';
        $route     = 'statistics';

        if ($user instanceof \App\Models\Operator) {
            $guardName = 'operator';
            $route     = 'home';
        }

        $this->auth->guard($guardName)->login($user);

        return \response()->redirectTo(route($route));
    }

    /**
     * @param string       $uuid
     * @param UrlGenerator $urlGenerator
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     * @throws \LogicException
     */
    public function impersonate(string $uuid, UrlGenerator $urlGenerator)
    {
        $user = $this->userRepository->find($uuid);

        $this->authorize('impersonate', $user);

        if (false !== $this->jwtAuth->getToken()) {
            $token = $this->jwtAuth->fromUser($user,
                [config('laravel-impersonate.session_key') => $this->user()->getKey()]);

            return \response()->render('', \compact('token'));
        }

        $this->user()->impersonate($user);

        session()->put('impersonate_last_url', $urlGenerator->previous());

        return \request()->wantsJson()
            ? \response()->render('', $this->user()->toArray())
            : \response()->redirectTo(route('statistics'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|Response
     * @throws \LogicException
     */
    public function stopImpersonate()
    {
        $this->user()->leaveImpersonation();

        return \request()->wantsJson()
            ? \response()->render('', [])
            : \response()->redirectTo(\request()->session()->get('impersonate_last_url'));
    }
}
