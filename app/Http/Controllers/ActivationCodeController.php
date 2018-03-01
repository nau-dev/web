<?php

namespace App\Http\Controllers;

use App\Repositories\ActivationCodeRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ActivationCodeController
 * NS: App\Http\Controllers
 */
class ActivationCodeController extends Controller
{
    private $activationCodeRepository;

    public function __construct(ActivationCodeRepository $activationCodeRepository, AuthManager $auth)
    {
        $this->activationCodeRepository = $activationCodeRepository;

        parent::__construct($auth);
    }

    public function show($code)
    {
        $activationCode = $this->activationCodeRepository
            ->findByCode($code);

        if (null === $activationCode) {
            throw (new ModelNotFoundException)->setModel($this->activationCodeRepository->model());
        }

        $this->authorize('activation_codes.show', $activationCode);

        $relationOffer         = $activationCode->offer->toArray();
        $searchResult          = $activationCode->toArray();
        $searchResult['offer'] = $relationOffer;

        return response()->render('activation_code.show', $searchResult);
    }
}
