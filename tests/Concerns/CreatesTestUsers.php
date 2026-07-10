<?php

namespace Tests\Concerns;

use App\Models\User;

/**
 * Manual replacement for RefreshDatabase in auth/profile tests: this project
 * has no separate test database (phpunit.xml runs against the real
 * connection), so RefreshDatabase's migrate:fresh would wipe live data.
 * Track every factory-created user by ID and delete exactly those rows in
 * tearDown() instead, the same pattern already used by
 * TranslationCacheResourceTest / TranslationServiceCacheTest.
 */
trait CreatesTestUsers
{
    protected array $testUserIds = [];

    protected function trackTestUser(User $user): User
    {
        $this->testUserIds[] = $user->id;

        return $user;
    }

    protected function tearDown(): void
    {
        User::whereIn('id', $this->testUserIds)->delete();

        parent::tearDown();
    }
}
