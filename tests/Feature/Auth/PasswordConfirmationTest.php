<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Livewire\Volt\Volt;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use CreatesTestUsers;

    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $user = $this->trackTestUser(User::factory()->create());

        $response = $this->actingAs($user)->get('/confirm-password');

        $response
            ->assertSeeVolt('pages.auth.confirm-password')
            ->assertStatus(200);
    }

    public function test_password_can_be_confirmed(): void
    {
        $user = $this->trackTestUser(User::factory()->create());

        $this->actingAs($user);

        $component = Volt::test('pages.auth.confirm-password')
            ->set('password', 'password');

        $component->call('confirmPassword');

        $component
            ->assertRedirect('/dashboard')
            ->assertHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $user = $this->trackTestUser(User::factory()->create());

        $this->actingAs($user);

        $component = Volt::test('pages.auth.confirm-password')
            ->set('password', 'wrong-password');

        $component->call('confirmPassword');

        $component
            ->assertNoRedirect()
            ->assertHasErrors('password');
    }
}
