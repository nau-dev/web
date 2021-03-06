<?php

namespace App\Http\Requests\Service;

use App\Helpers\Constants;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserRequest
 * NS: App\Http\Requests\Service
 *
 * @property string email
 * @property string phone
 * @property double balance
 */
class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $now = Carbon::now();
        $min = $now->copy()->subMinutes(2);

        return [
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|regex:/\+[0-9]{10,15}/|unique:users,phone',
            'balance'     => 'required|numeric',
            'timestamp'   => sprintf('required|integer|min:%d|max:%d', $min->timestamp, $now->timestamp),
            'signature'   => sprintf('required|string'),
            'eth_address' => sprintf('nullable|string|regex:%1$s|unique:users,eth_address', Constants::ETH_ADDRESS_REGEX),
        ];
    }
}
