<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

test('unauthenticated users are sent to login screen', function () {
    $response = $this->get(route('posts.index'));

    expect($response)->assertRedirect(route('login'));
});

test('admin can see posts list', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $response = $this->get(route('posts.index'));

    expect($response)->assertOk();
});

test('user cannot see posts list', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('posts.index'));

    expect($response)->assertStatus(403);
});

test('admin can see post create form', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $response = $this->get(route('posts.create'));

    expect($response)->assertOk()
        ->and($response)->assertViewIs('posts.create');
});

test('user cannot see post create form', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('posts.create'));

    expect($response)->assertStatus(403);
});

test('admin can create post', function () {
    $admin = User::factory()->admin()->create();

    $category = Category::factory()->create();

    $this->actingAs($admin);

    $response = $this->post(route('posts.store'), [
        'title' => 'Test Post',
        'text' => 'Test Content',
        'category_id' => $category->id,
    ]);

    expect($response)->assertRedirect(route('posts.index'));

    $post = Post::latest('id')->first();

    expect($post->title)->toBe('Test Post')
        ->and($post->text)->toBe('Test Content')
        ->and($post->category_id)->toBe($category->id);
});

test('user cannot create post', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('posts.store'), [
        'title' => 'Test Post',
        'text' => 'Test Content',
        'category_id' => $category->id,
    ]);

    expect($response)->assertStatus(403);

    $post = Post::latest('id')->first();

    expect($post)->toBeNull();
});

test('admin can see post details', function () {
    $admin = User::factory()->admin()->create();

    $post = Post::factory()->create();

    $this->actingAs($admin);

    $response = $this->get(route('posts.show', $post));

    expect($response)->assertOk()
        ->and($response)->assertViewHas('post', $post);
});

test('user cannot see post details', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('posts.show', $post));

    expect($response)->assertStatus(403);
});

test('admin can see post edit form', function () {
    $admin = User::factory()->admin()->create();

    $post = Post::factory()->create();

    $this->actingAs($admin);

    $response = $this->get(route('posts.edit', $post));

    expect($response)->assertOk()
        ->and($response)->assertViewIs('posts.edit')
        ->and($response)->assertViewHas('post', $post);
});

test('user cannot see post edit form', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('posts.edit', $post));

    expect($response)->assertStatus(403);
});

test('admin can update post', function () {
    $admin = User::factory()->admin()->create();

    $post = Post::factory()->create();

    $response = $this->actingAs($admin)
        ->patch(route('posts.update', $post), [
            'title' => 'New Post',
            'text' => 'New Content',
            'category_id' => $post->category_id,
        ]);

    expect($response)->assertRedirect(route('posts.index'));

    $post->refresh();

    expect($post->title)->toBe('New Post')
        ->and($post->text)->toBe('New Content');
});

test('user cannot update post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $response = $this->actingAs($user)
        ->patch(route('posts.update', $post), [
            'title' => 'New Post',
            'text' => 'New Content',
            'category_id' => $post->category_id,
        ]);

    expect($response)->assertStatus(403);

    $post->refresh();

    expect($post->title)->not()->toBe('New Post')
        ->and($post->text)->not()->toBe('New Content');
});

test('admin can delete post', function () {
    $admin = User::factory()->admin()->create();

    $post = Post::factory()->create();

    $response = $this->actingAs($admin)
        ->delete(route('posts.destroy', $post));

    expect($response)->assertRedirect(route('posts.index'))
        ->and($post->fresh())->toBeNull();
});

test('user cannot delete post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $response = $this->actingAs($user)
        ->delete(route('posts.destroy', $post));

    expect($response)->assertStatus(403)
        ->and($post->fresh())->not()->toBeNull();
});

test('post title is required', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $this->actingAs($admin);

    $response = $this->post(route('posts.store'), [
        'title' => '',
        'text' => 'Test Content',
        'category_id' => $category->id,
    ]);

    expect($response)->assertSessionHasErrors('title')
        ->and($response)->assertRedirect();

    $post = Post::latest('id')->first();

    expect($post)->toBeNull();
});

test('post title cannot exceed 255 characters', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $this->actingAs($admin);

    $tooLongTitle = str_repeat('a', 256); // 256 characters

    $response = $this->post(route('posts.store'), [
        'title' => $tooLongTitle,
        'text' => 'Test Content',
        'category_id' => $category->id,
    ]);

    expect($response)->assertSessionHasErrors('title')
        ->and($response)->assertRedirect();

    $post = Post::latest('id')->first();

    expect($post)->toBeNull();
});

test('post text is required', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $this->actingAs($admin);

    $response = $this->post(route('posts.store'), [
        'title' => 'Test Post',
        'text' => '',
        'category_id' => $category->id,
    ]);

    expect($response)->assertSessionHasErrors('text')
        ->and($response)->assertRedirect();

    $post = Post::latest('id')->first();

    expect($post)->toBeNull();
});

test('post category_id is required', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $response = $this->post(route('posts.store'), [
        'title' => 'Test Post',
        'text' => 'Test Content',
        'category_id' => '',
    ]);

    expect($response)->assertSessionHasErrors('category_id')
        ->and($response)->assertRedirect();

    $post = Post::latest('id')->first();

    expect($post)->toBeNull();
});

test('post category_id must be an integer', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $response = $this->post(route('posts.store'), [
        'title' => 'Test Post',
        'text' => 'Test Content',
        'category_id' => 'not-an-integer',
    ]);

    expect($response)->assertSessionHasErrors('category_id')
        ->and($response)->assertRedirect();

    $post = Post::latest('id')->first();

    expect($post)->toBeNull();
});

test('post category_id must exist in categories table', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $nonExistentCategoryId = 9999;

    $response = $this->post(route('posts.store'), [
        'title' => 'Test Post',
        'text' => 'Test Content',
        'category_id' => $nonExistentCategoryId,
    ]);

    expect($response)->assertSessionHasErrors('category_id')
        ->and($response)->assertRedirect();

    $post = Post::latest('id')->first();

    expect($post)->toBeNull();
});

test('post update requires title', function () {
    $admin = User::factory()->admin()->create();
    $post = Post::factory()->create();

    $this->actingAs($admin);

    $response = $this->patch(route('posts.update', $post), [
        'title' => '',
        'text' => 'Updated Content',
        'category_id' => $post->category_id,
    ]);

    expect($response)->assertSessionHasErrors('title')
        ->and($response)->assertRedirect();

    $post->refresh();
    expect($post->text)->not()->toBe('Updated Content');
});

test('post update requires text', function () {
    $admin = User::factory()->admin()->create();
    $post = Post::factory()->create();

    $this->actingAs($admin);

    $response = $this->patch(route('posts.update', $post), [
        'title' => 'Updated Title',
        'text' => '',
        'category_id' => $post->category_id,
    ]);

    expect($response)->assertSessionHasErrors('text')
        ->and($response)->assertRedirect();

    $post->refresh();
    expect($post->title)->not()->toBe('Updated Title');
});

test('post update requires category_id', function () {
    $admin = User::factory()->admin()->create();
    $post = Post::factory()->create();

    $this->actingAs($admin);

    $response = $this->patch(route('posts.update', $post), [
        'title' => 'Updated Title',
        'text' => 'Updated Content',
        'category_id' => '',
    ]);

    expect($response)->assertSessionHasErrors('category_id')
        ->and($response)->assertRedirect();

    $post->refresh();
    expect($post->title)->not()->toBe('Updated Title')
        ->and($post->text)->not()->toBe('Updated Content');
});

test('post update category_id must be an integer', function () {
    $admin = User::factory()->admin()->create();
    $post = Post::factory()->create();

    $this->actingAs($admin);

    $response = $this->patch(route('posts.update', $post), [
        'title' => 'Updated Title',
        'text' => 'Updated Content',
        'category_id' => 'not-an-integer',
    ]);

    expect($response)->assertSessionHasErrors('category_id')
        ->and($response)->assertRedirect();

    $post->refresh();
    expect($post->title)->not()->toBe('Updated Title')
        ->and($post->text)->not()->toBe('Updated Content');
});

test('post update category_id must exist in categories table', function () {
    $admin = User::factory()->admin()->create();
    $post = Post::factory()->create();

    $this->actingAs($admin);

    $nonExistentCategoryId = 9999;

    $response = $this->patch(route('posts.update', $post), [
        'title' => 'Updated Title',
        'text' => 'Updated Content',
        'category_id' => $nonExistentCategoryId,
    ]);

    expect($response)->assertSessionHasErrors('category_id')
        ->and($response)->assertRedirect();

    $post->refresh();
    expect($post->title)->not()->toBe('Updated Title')
        ->and($post->text)->not()->toBe('Updated Content');
});
