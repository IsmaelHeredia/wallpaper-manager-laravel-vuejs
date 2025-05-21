<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    protected function authUser()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user, 'api');

        return $user;
    }
}
