<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OfferRequest
 * @package App\Http\Requests\User
 *
 * @property string category
 * @property string latitude
 * @property string longitude
 * @property int radius
 *
 */
class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'string|exists:categories,id',
            'latitude'    => 'string|nullable',
            'longitude'   => 'string|nullable',
            'radius'      => 'integer|nullable'
        ];
    }
} 
