<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use \Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LoginController extends Controller
{

    use ThrottlesLogins;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        return response()->render('auth.login', [
            'email'    => null,
            'password' => null
        ]);
    }

    /**
     * @param LoginRequest $request
     * @return Response|\Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password', 'phone', 'code');

        return $request->wantsJson() ? $this->loginJwt($credentials) : $this->loginSession($credentials);
    }

    /**
     * @param array $credentials
     * @return Response
     */
    private function loginJwt(array $credentials): Response
    {
        $token = null;

        try {
            if (false === $token = \JWTAuth::attempt($credentials)) {
                return response()->error(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $credentials['phone'] ? trans('errors.invalid_code') : trans('errors.invalid_email_or_password')
                );
            }
        } catch (JWTException $e) {
            return response()->error(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }

        return response()->render('', compact('token'));
    }

    /**
     * @param array $credentials
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginSession(array $credentials)
    {
        $attempt = \Auth::attempt($credentials, false);

        if (false === $attempt) {
            session()->flash('message', trans('auth.failed'));
            return redirect()->route('loginForm');
        }

        return redirect(request()->get('redirect_to', '/'));
    }

    /**
     * @param string $phone
     * @return Response
     */
    public function sendSmsCode(string $phone): Response
    {
        $user = User::findByPhone($phone);

        if($user === null){
            return \response()->error(Response::HTTP_NOT_FOUND, 'User with phone ' . $phone . ' not found.');
        }

        cache()->put($user->phone, app(\App\Helpers\SmsAuth::class)->getCode($user->phone), 5);

        return \response()->render('auth.sms.success', ['phone_number' => $user->phone, 'code' => null], Response::HTTP_ACCEPTED, route('register'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout(\Illuminate\Http\Request $request)
    {
        if ($request->wantsJson()) {
            try {
                $logout = \JWTAuth::parseToken()->invalidate();
            } catch (JWTException $e) {
                return response()->error(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $e->getMessage()
                );
            }
            return response()->render('', compact('logout'));
        }

        auth()->logout();
        return redirect()->route('home');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenRefresh()
    {
        $token = \JWTAuth::getToken();

        if (!$token) {
            return \response()->error(Response::HTTP_BAD_REQUEST, 'Token not provided');
        }

        try {
            $token = \JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            return \response()->error(Response::HTTP_UNAUTHORIZED, 'The token is invalid');
        }

        return response()->json(compact('token'));
    }
}
