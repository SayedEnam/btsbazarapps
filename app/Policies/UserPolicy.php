<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    /**
     * View/create/edit checks go through the plain `users.*` permission
     * slugs (see Gate::before in AppServiceProvider) since they carry no
     * object-level nuance. Delete is the one action with invariants that
     * must hold even for Super Admin, so it alone gets a real Policy: nobody
     * may delete their own account, and the last Super Admin must always
     * remain so the system can never lock itself out.
     */
    public function delete(User $user, User $model): bool
    {
        if (! $user->hasRole(Role::SUPER_ADMIN) && ! $user->hasPermission('users.delete')) {
            return false;
        }

        if ($user->is($model)) {
            return false;
        }

        if ($model->hasRole(Role::SUPER_ADMIN)) {
            return Role::where('slug', Role::SUPER_ADMIN)->first()?->users()->count() > 1;
        }

        return true;
    }
}
