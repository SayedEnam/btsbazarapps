<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class ReferralController extends Controller
{
    /**
     * Capture a referral code from /r/{code} into the session, then send the
     * visitor to the registration page. The code is stored in session — not
     * relied on staying in the URL — so it survives however long the
     * visitor takes to actually register (spec section 7).
     */
    public function capture(string $code): RedirectResponse
    {
        $officer = User::where('referral_code', $code)
            ->whereHas('roles', fn ($query) => $query->where('slug', Role::MARKETING_OFFICER))
            ->first();

        if ($officer) {
            Session::put('referral_code', $code);
        } else {
            Session::flash('status', 'That referral link is not valid, but you can still register below.');
        }

        return redirect()->route('register');
    }
}
