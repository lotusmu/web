<?php

namespace Tests;

use App\Models\User\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'name' => fakeUsername(),
            'email' => fakeEmail(),
            'password' => fakePassword(),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);
    }
}
