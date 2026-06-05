<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
//  PEST TEST
uses(RefreshDatabase::class);

// ─────────────────────────────────────────────
// INDEX
// ─────────────────────────────────────────────

test('index page loads successfully', function () {
    $response = $this->get(route('posts.index'));

    $response->assertStatus(200);
    $response->assertViewIs('posts.index');
});

test('index page displays list of posts', function () {
    $posts = Post::factory()->count(3)->create();

    $response = $this->get(route('posts.index'));

    $response->assertStatus(200);
    $response->assertViewHas('posts');

    foreach ($posts as $post) {
        $response->assertSee($post->title);
    }
});

test('index page shows empty state when no posts exist', function () {
    $response = $this->get(route('posts.index'));

    $response->assertStatus(200);
    $response->assertSee('No posts found.');
});

// ─────────────────────────────────────────────
// CREATE
// ─────────────────────────────────────────────

test('create page loads successfully', function () {
    $response = $this->get(route('posts.create'));

    $response->assertStatus(200);
    $response->assertViewIs('posts.create');
});

// ─────────────────────────────────────────────
// STORE
// ─────────────────────────────────────────────

test('a new post can be stored', function () {
    $response = $this->post(route('posts.store'), [
        'title'   => 'My First Blog Post',
        'content' => 'This is the content of my first blog post.',
        'status'  => 'publish',
    ]);

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('success', 'Post created successfully.');

    $this->assertDatabaseHas('posts', [
        'title'   => 'My First Blog Post',
        'slug'    => 'my-first-blog-post',
        'content' => 'This is the content of my first blog post.',
        'status'  => 'publish',
    ]);
});

test('slug is automatically generated from title', function () {
    $this->post(route('posts.store'), [
        'title'   => 'Laravel 13 Is Amazing',
        'content' => 'Some content here.',
        'status'  => 'draft',
    ]);

    $this->assertDatabaseHas('posts', [
        'title' => 'Laravel 13 Is Amazing',
        'slug'  => 'laravel-13-is-amazing',
    ]);
});

test('store validates required fields', function () {
    $response = $this->post(route('posts.store'), []);

    $response->assertSessionHasErrors(['title', 'content', 'status']);
});

test('store validates title max length', function () {
    $response = $this->post(route('posts.store'), [
        'title'   => str_repeat('a', 256),
        'content' => 'Some content.',
        'status'  => 'publish',
    ]);

    $response->assertSessionHasErrors(['title']);
});

test('store validates status must be draft or publish', function () {
    $response = $this->post(route('posts.store'), [
        'title'   => 'Test Post',
        'content' => 'Some content.',
        'status'  => 'archived',
    ]);

    $response->assertSessionHasErrors(['status']);
});

test('store validates slug uniqueness', function () {
    Post::factory()->create(['title' => 'Duplicate Title', 'slug' => 'duplicate-title']);

    $response = $this->post(route('posts.store'), [
        'title'   => 'Duplicate Title',
        'content' => 'Different content.',
        'status'  => 'draft',
    ]);

    $response->assertSessionHasErrors(['slug']);
});

// ─────────────────────────────────────────────
// SHOW
// ─────────────────────────────────────────────

test('show page displays a single post', function () {
    $post = Post::factory()->create();

    $response = $this->get(route('posts.show', $post));

    $response->assertStatus(200);
    $response->assertViewIs('posts.show');
    $response->assertSee($post->title);
    $response->assertSee($post->content);
});

test('show returns 404 for non-existent post', function () {
    $response = $this->get(route('posts.show', 9999));

    $response->assertStatus(404);
});

// ─────────────────────────────────────────────
// EDIT
// ─────────────────────────────────────────────

test('edit page displays form with existing data', function () {
    $post = Post::factory()->create();

    $response = $this->get(route('posts.edit', $post));

    $response->assertStatus(200);
    $response->assertViewIs('posts.edit');
    $response->assertSee($post->title);
    $response->assertSee($post->content);
});

test('edit returns 404 for non-existent post', function () {
    $response = $this->get(route('posts.edit', 9999));

    $response->assertStatus(404);
});

// ─────────────────────────────────────────────
// UPDATE
// ─────────────────────────────────────────────

test('a post can be updated', function () {
    $post = Post::factory()->create([
        'title'   => 'Original Title',
        'slug'    => 'original-title',
        'content' => 'Original content.',
        'status'  => 'draft',
    ]);

    $response = $this->put(route('posts.update', $post), [
        'title'   => 'Updated Title',
        'content' => 'Updated content.',
        'status'  => 'publish',
    ]);

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('success', 'Post updated successfully.');

    $this->assertDatabaseHas('posts', [
        'id'      => $post->id,
        'title'   => 'Updated Title',
        'slug'    => 'updated-title',
        'content' => 'Updated content.',
        'status'  => 'publish',
    ]);
});

test('update validates required fields', function () {
    $post = Post::factory()->create();

    $response = $this->put(route('posts.update', $post), []);

    $response->assertSessionHasErrors(['title', 'content', 'status']);
});

test('update allows same slug on same post', function () {
    $post = Post::factory()->create([
        'title' => 'Same Title',
        'slug'  => 'same-title',
    ]);

    $response = $this->put(route('posts.update', $post), [
        'title'   => 'Same Title',
        'content' => 'Updated content.',
        'status'  => 'publish',
    ]);

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHasNoErrors();
});

// ─────────────────────────────────────────────
// DESTROY
// ─────────────────────────────────────────────

test('a post can be deleted', function () {
    $post = Post::factory()->create();

    $response = $this->delete(route('posts.destroy', $post));

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('success', 'Post deleted successfully.');

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

test('destroy returns 404 for non-existent post', function () {
    $response = $this->delete(route('posts.destroy', 9999));

    $response->assertStatus(404);
});
