<?php

namespace App\Traits;

use App\Entities\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait CompanyAuthorization
{
    /**
     * Check company role
     *
     * @param string $role
     * @param Company $company
     * @return bool
     */
    public function hasCompanyRole(string $role, Company $company)
    {
        $role = Role::where('name', $role)->first();

        if ($role) {
           $result = $this->userCompanyRole()->where('company_id', $company->id)->where('role_id', $role->id)->count();
           return $result;
        } else {
            return false;
        }
    }

    /**
     * Check company permission
     *
     * @param string $permission
     * @param Company $company
     * @return bool
     */
    public function canCompanyPermission(string $permission, Company $company)
    {
        $permission = Permission::where('name', $permission)->first();

        if ($permission) {
            $result = $this->userCompanyPermission()->where('company_id', $company->id)->where('permission_id', $permission->id)->count();
            return $result;
        } else {
            return false;
        }

    }
}
