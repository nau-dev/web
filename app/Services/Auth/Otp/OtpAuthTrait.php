<?php

namespace App\Services\Auth\Otp;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

trait OtpAuthTrait
{
    /**
     * @var string
     */
    protected $gateName;

    /**
     * @var Client
     */
    protected $configData;

    public function __construct()
    {

        if (!isset($this->client)) {
            $this->client = new Client([
                'base_uri' => $this->configData['base_api_url']
            ]);
        }
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->configData = config('otp.gate_data.' . $this->gateName);
    }

    /**
     * @param string $phoneNumber
     * @param string $codeToCheck
     *
     * @return bool
     */
    public function validateCode(string $phoneNumber, string $codeToCheck): bool
    {
        return Cache::has($phoneNumber)
            ? Hash::check($codeToCheck, Cache::get($phoneNumber))
            : false;
    }

    /**
     * @param string $phoneNumber
     * @param string $code
     */
    protected function cacheOtpCode(string $phoneNumber, string $code)
    {
        Cache::put($phoneNumber, Hash::make($code), 15);
    }

    /**
     * @param string      $loggerMessage
     * @param string|null $exceptionMessage
     *
     * @throws UnprocessableEntityHttpException
     */
    protected function otpError(string $loggerMessage, string $exceptionMessage = null)
    {
        $exceptionMessage = $exceptionMessage ?: 'Can\'t send otp code. Try again later.';
        logger('OTP: ' . $loggerMessage . ' Gate:' . $this->gateName);
        throw new UnprocessableEntityHttpException($exceptionMessage);
    }

    /**
     * @return string
     */
    protected function createOtp(): string
    {
        return (string)random_int(100000, 999999);
    }

    protected function getOtpMessage($code)
    {
        return 'NAU verification code: ' . $code;
    }
}