<?php

namespace App\Http\Requests\Advert;

use App\Helpers\Constants;
use App\Models\OfferData;
use App\Services\OfferReservation;
use App\Services\WeekDaysService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Class OfferRequest
 * @package App\Http\Requests\Auth
 *
 * @property string         label
 * @property string         description
 * @property float          reward
 * @property \Carbon\Carbon start_date
 * @property \Carbon\Carbon finish_date
 * @property string         category_id
 * @property int            max_count
 * @property int            max_for_user
 * @property int            max_per_day
 * @property int|null       max_for_user_per_day
 * @property int|null       max_for_user_per_week
 * @property int|null       max_for_user_per_month
 * @property int            user_level_min
 * @property string         latitude
 * @property string         longitude
 * @property int            radius
 * @property string         country
 * @property string         city
 * @property int            timeframes_offset
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
        $weekDaysService  = app(WeekDaysService::class);
        $offerReservation = app(OfferReservation::class);

        return [
            'label'                  => 'required|string|min:3|max:40',
            'description'            => 'required|string',
            'reward'                 => 'required|numeric|min:1',
            'start_date'             => 'required|date|date_format:' . Constants::DATE_FORMAT,
            'finish_date'            => 'nullable|date|date_format:' . Constants::DATE_FORMAT,
            'category_id'            => sprintf(
                'required|string|regex:%s|exists:categories,id',
                Constants::UUID_REGEX
            ),
            'max_count'              => 'nullable|integer|min:1',
            'max_for_user'           => 'nullable|integer|min:1',
            'max_per_day'            => 'nullable|integer|min:1',
            'max_for_user_per_day'   => 'nullable|integer|min:1',
            'max_for_user_per_week'  => 'nullable|integer|min:1',
            'max_for_user_per_month' => 'nullable|integer|min:1',
            'user_level_min'         => 'required|integer|min:1',
            'latitude'               => 'nullable|numeric|between:-90,90',
            'longitude'              => 'nullable|numeric|between:-180,180',
            'radius'                 => 'nullable|integer',
            'country'                => 'nullable|string',
            'city'                   => 'nullable|string',
            'reserved'               => sprintf(
                'required|numeric|min:%s',
                $offerReservation->getMinReserved($this->get('reward'))
            ),
            'timeframes'             => 'required|array',
            'timeframes.*.from'      => 'required|date_format:' . Constants::TIME_FORMAT,
            'timeframes.*.to'        => 'required|date_format:' . Constants::TIME_FORMAT,
            'timeframes.*.days'      => 'required|array',
            'timeframes.*.days.*'    => sprintf(
                'required|string|in:%s',
                implode(',', $weekDaysService->fullList())
            ),
            'timeframes_offset'      => 'required|integer',
            'delivery'               => 'required|boolean',
            'type'                   => sprintf(
                'nullable|required_with:gift_bonus_descr,discount_percent|in:%s',
                implode(',', OfferData::OFFER_TYPES)
            ),
            'gift_bonus_descr'       => sprintf(
                'nullable|required_if:type,%s,type,%s|string',
                OfferData::OFFER_TYPE_GIFT,
                OfferData::OFFER_TYPE_BONUS
            ),
            'discount_percent'       => sprintf(
                'nullable|required_if:type,%s|required_with:discount_start_price|numeric|between:0,100',
                OfferData::OFFER_TYPE_DISCOUNT
            ),
            'discount_start_price'   => 'nullable|required_with:currency|numeric',
            'currency'               => sprintf(
                'nullable|required_with:discount_start_price|string|in:%s',
                implode(',', Constants::CURRENCIES)
            ),
            'points' => 'required|integer',
            'is_featured'             => 'nullable|bool',
            'referral_points_price'   => 'nullable|integer|min:0',
            'redemption_points_price' => 'nullable|integer|min:0'
        ];
    }

    /**
     * @param  Validator  $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $description = str_replace("\r\n", "\n", request()->get('description'));

            if (mb_strlen($description) > 200) {
                $validator->errors()->add('error', trans('validation.description_max_size'));
            }
        });
    }
} 
