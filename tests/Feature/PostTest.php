<?php

declare(strict_types=1);

use App\Models\User;

test('unauthenticated users are sent to login screen', function () {
    $response = $this->get(route('posts.index'));

    expect($response)->assertRedirect(route('login'));
});

test('admin can see posts list', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user);

    $response = $this->get(route('posts.index'));

    expect($response)->assertOk();
});

test('user cannot see posts list', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('posts.index'));

    expect($response)->assertStatus(403);
});
