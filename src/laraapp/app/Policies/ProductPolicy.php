<?php

namespace App\Policies;

use App\Entities\Company;
use App\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create product.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function store(User $user, Company $company)
    {
        return (($user->id === $company->user_id) ||
            ($user->hasCompanyRole('admin', $company)) ||
            ($user->canCompanyPermission('update', $company)));
    }

    /**
     * Determine whether the user can update product.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function update(User $user, Company $company)
    {
        return (($user->id === $company->user_id) ||
            ($user->hasCompanyRole('admin', $company)) ||
            ($user->canCompanyPermission('update', $company)));
    }

    /**
     * Determine whether the user can delete product.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function delete(User $user, Company $company)
    {
        return (($user->id === $company->user_id) ||
            ($user->hasCompanyRole('admin', $company)) ||
            ($user->canCompanyPermission('update', $company)));
    }

    /**
     * Determine whether the user can show product.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function show(User $user, Company $company)
    {
        return (($user->canCompanyPermission('update', $company)) || ($user->canCompanyPermission('view', $company)));
    }

    /**
     * Determine whether the user can list products.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function list(User $user, Company $company)
    {
        return (($user->canCompanyPermission('update', $company)) || ($user->canCompanyPermission('view', $company)));
    }

    /**
     * Determine whether the user can search products.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function search(User $user, Company $company)
    {
        return (($user->canCompanyPermission('update', $company)) || ($user->canCompanyPermission('view', $company)));
    }
}
