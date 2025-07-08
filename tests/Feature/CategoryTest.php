<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;

test('unauthenticated users are sent to login screen', function () {
    $response = $this->get(route('categories.index'));

    expect($response)->assertRedirect(route('login'));
});

test('admin can see category list', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $response = $this->get(route('categories.index'));

    expect($response)->assertStatus(200);
});

test('user cannot see category list', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('categories.index'));

    expect($response)->assertStatus(403);
});

test('admin can see category create form', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $response = $this->get(route('categories.create'));

    expect($response)->assertOk()
        ->and($response)->assertViewIs('categories.create');
});

test('user cannot see category create form', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('categories.create'));

    expect($response)->assertStatus(403);
});

test('admin can create category', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $response = $this->post(route('categories.store'), [
        'name' => 'Test Category',
    ]);

    expect($response)->assertRedirect(route('categories.index'));

    $category = Category::latest('id')->first();

    expect($category->name)->toBe('Test Category');
});

test('user cannot create category', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('categories.store'), [
            'name' => 'Test Category',
        ]);

    expect($response)->assertStatus(403);

    $category = Category::latest('id')->first();

    expect($category)->toBeNull();
});

test('admin can see category details', function () {
    $admin = User::factory()->admin()->create();

    $category = Category::factory()->create();

    $this->actingAs($admin);

    $response = $this->get(route('categories.show', $category));

    expect($response)->assertOk()
        ->and($response)->assertViewHas('category', $category);
});

test('user cannot see category details', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('categories.show', $category));

    expect($response)->assertStatus(403);
});

test('admin can see category edit form', function () {
    $admin = User::factory()->admin()->create();

    $category = Category::factory()->create();

    $this->actingAs($admin);

    $response = $this->get(route('categories.edit', $category));

    expect($response)->assertOk()
        ->and($response)->assertViewIs('categories.edit')
        ->and($response)->assertViewHas('category', $category);
});

test('user cannot see category edit form', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('categories.edit', $category));

    expect($response)->assertStatus(403);
});

test('admin can update category', function () {
    $admin = User::factory()->admin()->create();

    $category = Category::factory()->create([
        'name' => 'Old Category',
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('categories.update', $category), [
            'name' => 'New Category',
        ]);

    expect($response)->assertRedirect(route('categories.index'));

    $category->refresh();

    expect($category->name)->toBe('New Category');
});

test('user cannot update category', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create([
        'name' => 'Old Category',
    ]);

    $response = $this->actingAs($user)
        ->patch(route('categories.update', $category), [
            'name' => 'New Category',
        ]);

    expect($response)->assertStatus(403);

    $category->refresh();

    expect($category->name)->toBe('Old Category');
});

test('admin can delete category', function () {
    $admin = User::factory()->admin()->create();

    $category = Category::factory()->create();

    $response = $this->actingAs($admin)
        ->delete(route('categories.destroy', $category));

    expect($response)->assertRedirect(route('categories.index'))
        ->and($category->fresh())->toBeNull();

});

test('user cannot delete category', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();

    $response = $this->actingAs($user)
        ->delete(route('categories.destroy', $category));

    expect($response)->assertStatus(403)
        ->and($category->fresh())->not()->toBeNull();
});
