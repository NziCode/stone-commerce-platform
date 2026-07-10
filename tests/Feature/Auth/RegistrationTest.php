<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Livewire\Volt\Volt;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use CreatesTestUsers;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    }

    public function test_new_users_can_register(): void
    {
        $email = 'registration-test-'.uniqid().'@example.com';

        try {
            $component = Volt::test('pages.auth.register')
                ->set('name', 'Test User')
                ->set('email', $email)
                ->set('password', 'password')
                ->set('password_confirmation', 'password');

            $component->call('register');

            $component->assertRedirect(route('dashboard', absolute: false));

            $this->assertAuthenticated();
        } finally {
            // Registration may have succeeded even if an assertion above threw
            // (e.g. redirect target mismatch) — always try to track it for cleanup.
            if ($user = User::where('email', $email)->first()) {
                $this->trackTestUser($user);
            }
        }
    }
}
