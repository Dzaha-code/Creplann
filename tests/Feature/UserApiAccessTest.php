<?php

use App\Models\User;

describe('User API access', function () {
    it('requires authentication for user listing and profile routes', function () {
        $this->getJson('/api/users')
            ->assertUnauthorized();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/users/' . $user->id)
            ->assertOk();
    });

    it('prevents cross-user access to another profile via the user api', function () {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($owner)
            ->getJson('/api/users/' . $other->id)
            ->assertForbidden();
    });
});
