<?php

namespace App\Providers;

use App\Models\NauModels\Offer;
use App\Models\User;
use App\Observers\OfferObserver;
use App\Observers\UserObserver;
use App\Repositories\PlaceRepository;
use App\Services\Implementation\NauOfferReservation;
use App\Repositories\Criteria\MappableRequestCriteria;
use App\Repositories\Criteria\MappableRequestCriteriaEloquent;
use App\Services\Implementation\InvestorAreaService as InvestorAreaServiceImpl;
use App\Services\Implementation\WeekDaysService as WeekDaysServiceImpl;
use App\Services\InvestorAreaService;
use App\Services\NauOffersService;
use App\Services\OfferReservation;
use App\Services\OffersService;
use App\Services\WeekDaysService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

/**
 * Class AppServiceProvider
 * @package App\Providers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'users' => User::class
        ]);

        Offer::observe(OfferObserver::class);
        User::observe(UserObserver::class);

        ViewFacade::composer(
            ['*'], function (View $view) {
                $authUser = auth()->user();
                if (null != $authUser) {
                    $authUser->load('accounts');
                    $view->with('authUser', $authUser->toArray());

                    $placesRepository = app(PlaceRepository::class);
                    $view->with('isPlaceCreated', $placesRepository->existsByUser($authUser));
                }
            }
        );

        $this->setUserViewsData();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            OffersService::class,
            NauOffersService::class);
        $this->app->bind(
            WeekDaysService::class,
            WeekDaysServiceImpl::class
        );
        $this->app->bind(
            OfferReservation::class,
            NauOfferReservation::class);
        $this->app->bind(
            MappableRequestCriteria::class,
            MappableRequestCriteriaEloquent::class
        );
        $this->app->bind(
            InvestorAreaService::class,
            InvestorAreaServiceImpl::class
        );
    }

    private function setUserViewsData()
    {
        ViewFacade::composer(
            ['user.show'], function (View $view) {
                $editableUserArray = $view->getData();
                /** @var User $editableUserModel */
                $editableUserModel = User::query()->find($editableUserArray['id']);
                $roleIds           = array_column(\App\Models\Role::query()->get(['id'])->toArray(), 'id');
                $children          = $editableUserModel->children->toArray();

                if (auth()->user()->isAdmin()) {
                    $allChildren = \App\Models\User::query()->get();
                } else {
                    $allChildren = auth()->user()->children;
                }

                $allPossibleChildren = [];


                if ($editableUserModel->isAgent()) {
                    $rolesForChildSet = [\App\Models\Role::ROLE_CHIEF_ADVERTISER, \App\Models\Role::ROLE_ADVERTISER];
                } else {
                    $rolesForChildSet = [\App\Models\Role::ROLE_ADVERTISER];
                }

                foreach ($allChildren as $childValue) {
                    if ($childValue->hasRoles($rolesForChildSet)) {
                        $allPossibleChildren[] = $childValue->toArray();
                    }
                }

                $view->with('roleIds', $roleIds);
                $view->with('children', $children);
                $view->with('allPossibleChildren', $allPossibleChildren);
                $view->with('editableUserModel', $editableUserModel);
            }
        );
    }
}
