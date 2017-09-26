<?php

namespace App\Http\Controllers\Offer;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Models\NauModels\Offer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Offer
 */
class PictureController extends AbstractPictureController
{
    private const OFFER_PICTURES_PATH = 'images/offer/pictures';

    /**
     * Saves offer image from request
     *
     * @param PictureRequest $request
     * @param string         $offerId
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(PictureRequest $request, string $offerId)
    {
        /** @var Offer $offer */
        $offer = Offer::byOwner(auth()->user())->findOrFail($offerId);

        return $this->storeImageFor($request, $offer->id, route('offer.picture.show', ['offerId' => $offerId]));
    }

    /**
     * Retrieves and responds with offer image
     *
     * @param string $offerId
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $offerId): Response
    {
        /** @var Offer $offer */
        $offer = Offer::findOrFail($offerId);

        return $this->respondWithImageFor($offer->id);
    }

    protected function getPath(): string
    {
        return self::OFFER_PICTURES_PATH;
    }
}