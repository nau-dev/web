<?php

namespace App\Policies;

use App\Exceptions\TokenException;
use App\Models\Role;
use App\Models\User;

class UserUpdatePolicy extends UserPolicy
{
    /**
     * @param User  $user
     * @param User  $editableUser
     * @param array $userData
     *
     * @return bool
     */
    public function update(User $user, User $editableUser, array $userData = [])
    {
        return ($this->editSelf($user, $editableUser, $userData) || $this->editChild($user, $editableUser))
            && (!isset($userData['invite_code']) || $this->updateInvite($user, $editableUser)) || $user->isAdmin();
    }

    /**
     * @param User $user
     * @param User $editableUser
     *
     * @return bool
     */
    public function updateInvite(User $user, User $editableUser)
    {
        try {
            $nauAccount = $editableUser->getAccountForNau();
        } catch (TokenException $exception) {

            return false;
        }

        return $editableUser->level >= (int)setting('min_level_for_change_invite') ||
            $nauAccount->getBalance() >= (int)setting('min_balance_for_change_invite') ||
            $user->isAdmin();
    }

    /**
     * @param User $user
     * @param User $editableUser
     *
     * @return bool
     */
    private function editSelf(User $user, User $editableUser, array $userData)
    {
        return $user->hasAnyRole() && $editableUser->equals($user) && !isset($userData['approved']);
    }

    /**
     * @param User $user
     * @param User $editableUser
     *
     * @return bool
     */
    private function editChild(User $user, User $editableUser)
    {
        return ($user->isAgent() || $user->isChiefAdvertiser()) && $user->hasChild($editableUser);
    }

    /**
     * @param User  $user
     * @param User  $editableUser
     * @param array $roleIds
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function updateRoles(User $user, User $editableUser, array $roleIds): bool
    {
        if (count($roleIds) > 1
            && count(array_diff([
                Role::findByName(Role::ROLE_ADVERTISER)->getId(),
                Role::findByName(Role::ROLE_USER)->getId()
                ], $roleIds)) > 0) {
            return false;
        }

        if(isset($roleIds[0])) {
            /**
             * @var Role $role
             */
            $role = (new Role)->findOrFail($roleIds[0]);
            if ($user->isAgent()
                && ($role->equalsByName(Role::ROLE_ADMIN)
                || $role->equalsByName(Role::ROLE_AGENT))) {
                return false;
            }
        }

        return $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }

    /**
     * @param User  $user
     * @param User  $editableUser
     * @param array $parentIds
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateParents(User $user, User $editableUser, array $parentIds)
    {
        foreach ($parentIds as $parentId) {
            /**
             * @var User $parentUser
             */
            $parentUser = (new User)->findOrFail($parentId);
            if (!$parentUser->equals($user) && !$parentUser->hasParent($user) && $user->isAgent()) {
                return false;
            }
            if (!$parentUser->hasRoles([Role::ROLE_AGENT, Role::ROLE_CHIEF_ADVERTISER])) {
                return false;
            }
        }

        return !$editableUser->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])
            && $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT, Role::ROLE_CHIEF_ADVERTISER]);
    }
}
