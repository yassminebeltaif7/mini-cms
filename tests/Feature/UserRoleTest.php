<?php

use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

it('lets an admin promote an author', function () {
    $admin = User::factory()->admin()->create();
    $author = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.role', $author), ['role' => 'admin'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Rôle mis à jour.');

    $this->assertDatabaseHas('users', ['id' => $author->id, 'role' => 'admin']);

    $this->actingAs($author->fresh())
        ->get(route('home'))
        ->assertSee(route('admin.tags.index'))
        ->assertSee(route('admin.users.index'));
});

it('forbids an admin to change their own role', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.role', $admin), ['role' => 'author'])
        ->assertForbidden();

    $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
});
