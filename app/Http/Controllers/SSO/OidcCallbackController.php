<?php

namespace App\Http\Controllers\SSO;

use App\Attributes\RequiresPlus;
use App\Http\Controllers\Controller;
use App\Services\AuthenticationService;
use App\Services\UserService;
use App\Values\SsoUser;
use Laravel\Socialite\Facades\Socialite;

#[RequiresPlus]
class OidcCallbackController extends Controller
{
    public function __invoke(AuthenticationService $auth, UserService $userService)
    {
        $user = Socialite::driver('oidc-generic')->user();
        $user = $userService->createOrUpdateUserFromSso(SsoUser::fromSocialite($user, 'OpenID Connect'));

        return view('sso-callback')->with('token', $auth->logUserIn($user)->toArray());
    }
}