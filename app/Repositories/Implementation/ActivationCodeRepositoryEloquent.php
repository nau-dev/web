<?php

namespace App\Repositories\Implementation;

use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use App\Models\User;
use App\Repositories\ActivationCodeRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ActivationCodeRepositoryEloquent
 * NS: App\Repositories
 *
 * @property ActivationCode $model
 */
class ActivationCodeRepositoryEloquent extends BaseRepository implements ActivationCodeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ActivationCode::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function findByCodeAndUser($code, User $user): ?ActivationCode
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byCode($code)->byOwner($user)->first();
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function findByCodeAndOfferAndNotRedeemed(string $code, Offer $offer): ?ActivationCode
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byCode($code)->byOffer($offer)->whereNull('redemption_id')->first();
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function findByCodeAndNotRedeemed(string $code): ?ActivationCode
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byCode($code)->whereNull('redemption_id')->firstOrFail();
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function findByCode(string $code): ?ActivationCode
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byCode($code)->firstOrFail();
        $this->resetModel();

        return $this->parserResult($model);
    }
}
