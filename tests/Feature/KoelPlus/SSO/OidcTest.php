<?php

namespace Tests\Feature\KoelPlus\SSO;

use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as OidcUser;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\PlusTestCase;

use function Tests\create_user;

class OidcTest extends PlusTestCase
{
    #[Test]
    public function callbackWithNewUser(): void
    {
        $oidcUser = Mockery::mock(OidcUser::class, [
            'getEmail' => 'john@example.com',
            'getName' => 'John Doe',
            'getAvatar' => 'https://example.com/avatar.jpg',
            'getId' => Str::random(),
        ]);

        Socialite::expects('driver->user')->andReturn($oidcUser);

        $response = $this->get('auth/oidc/callback');
        $response->assertOk();
        $response->assertViewIs('sso-callback');
        $response->assertViewHas('token');
    }

    #[Test]
    public function callbackWithExistingEmail(): void
    {
        create_user(['email' => 'john@example.com']);

        $oidcUser = Mockery::mock(OidcUser::class, [
            'getEmail' => 'john@example.com',
            'getName' => 'John Doe',
            'getAvatar' => 'https://example.com/avatar.jpg',
            'getId' => Str::random(),
        ]);

        Socialite::expects('driver->user')->andReturn($oidcUser);

        $response = $this->get('auth/oidc/callback');
        $response->assertOk();
        $response->assertViewIs('sso-callback');
        $response->assertViewHas('token');
    }

    #[Test]
    public function callbackWithExistingSSOUser(): void
    {
        create_user([
            'sso_provider' => 'OpenID Connect',
            'sso_id' => '456',
            'email' => 'john@example.com',
        ]);

        $oidcUser = Mockery::mock(OidcUser::class, [
            'getEmail' => 'john@example.com',
            'getName' => 'John Doe',
            'getAvatar' => 'https://example.com/avatar.jpg',
            'getId' => '456',
        ]);

        Socialite::expects('driver->user')->andReturn($oidcUser);

        $response = $this->get('auth/oidc/callback');
        $response->assertOk();
        $response->assertViewIs('sso-callback');
        $response->assertViewHas('token');
    }
}