<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Only short-circuit permission-slug abilities (e.g. `can('users.view')`).
        // Bare-word abilities (view/create/update/delete on an Eloquent model)
        // are left to their Policy, so hard invariants that must hold even for
        // Super Admin — never delete yourself, never remove the last Super
        // Admin — actually run instead of being skipped by a blanket bypass.
        // Policies grant Super Admin explicitly where no such invariant applies.
        Gate::before(function (User $user, string $ability) {
            if (! str_contains($ability, '.')) {
                return null;
            }

            if ($user->hasRole(Role::SUPER_ADMIN)) {
                return true;
            }

            return $user->hasPermission($ability) ? true : null;
        });
    }
}
