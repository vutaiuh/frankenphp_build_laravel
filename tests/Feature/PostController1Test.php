<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
// PHP UNIT TEST

class PostController1Test extends TestCase
{
    use RefreshDatabase;

    // INDEX
    public function test_index_page_loads_successfully(): void
    {
        $this->get(route('posts.index'))->assertStatus(200)->assertViewIs('posts.index');
    }

    public function test_index_page_displays_list_of_posts(): void
    {
        $posts = Post::factory()->count(3)->create();
        $response = $this->get(route('posts.index'));
        $response->assertStatus(200)->assertViewHas('posts');
        foreach ($posts as $post) {
            $response->assertSee($post->title);
        }
    }

    public function test_index_shows_empty_state(): void
    {
        $this->get(route('posts.index'))->assertSee('No posts found.');
    }

    // CREATE
    public function test_create_page_loads_successfully(): void
    {
        $this->get(route('posts.create'))->assertStatus(200)->assertViewIs('posts.create');
    }

    // STORE
    public function test_a_new_post_can_be_stored(): void
    {
        $response = $this->post(route('posts.store'), [
            'title'   => 'My First Blog Post',
            'content' => 'This is the content.',
            'status'  => 'publish',
        ]);
        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success', 'Post created successfully.');
        $this->assertDatabaseHas('posts', ['title' => 'My First Blog Post', 'slug' => 'my-first-blog-post']);
    }

    public function test_slug_is_generated_from_title(): void
    {
        $this->post(route('posts.store'), ['title' => 'Laravel 13 Is Amazing', 'content' => 'Content.', 'status' => 'draft']);
        $this->assertDatabaseHas('posts', ['slug' => 'laravel-13-is-amazing']);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->post(route('posts.store'), [])->assertSessionHasErrors(['title', 'content', 'status']);
    }

    public function test_store_validates_title_max_length(): void
    {
        $this->post(route('posts.store'), ['title' => str_repeat('a', 256), 'content' => 'Content.', 'status' => 'publish'])
             ->assertSessionHasErrors(['title']);
    }

    public function test_store_validates_invalid_status(): void
    {
        $this->post(route('posts.store'), ['title' => 'Test', 'content' => 'Content.', 'status' => 'archived'])
             ->assertSessionHasErrors(['status']);
    }

    public function test_store_validates_slug_uniqueness(): void
    {
        Post::factory()->create(['title' => 'Duplicate Title', 'slug' => 'duplicate-title']);
        $this->post(route('posts.store'), ['title' => 'Duplicate Title', 'content' => 'Content.', 'status' => 'draft'])
             ->assertSessionHasErrors(['slug']);
    }

    // SHOW
    public function test_show_page_displays_a_post(): void
    {
        $post = Post::factory()->create();
        $this->get(route('posts.show', $post))->assertStatus(200)->assertViewIs('posts.show')->assertSee($post->title);
    }

    public function test_show_returns_404_for_nonexistent_post(): void
    {
        $this->get(route('posts.show', 9999))->assertStatus(404);
    }

    // EDIT
    public function test_edit_page_displays_form_with_existing_data(): void
    {
        $post = Post::factory()->create();
        $this->get(route('posts.edit', $post))->assertStatus(200)->assertViewIs('posts.edit')->assertSee($post->title);
    }

    public function test_edit_returns_404_for_nonexistent_post(): void
    {
        $this->get(route('posts.edit', 9999))->assertStatus(404);
    }

    // UPDATE
    public function test_a_post_can_be_updated(): void
    {
        $post = Post::factory()->create(['title' => 'Original', 'slug' => 'original', 'content' => 'Old.', 'status' => 'draft']);
        $response = $this->put(route('posts.update', $post), ['title' => 'Updated Title', 'content' => 'New content.', 'status' => 'publish']);
        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title', 'slug' => 'updated-title']);
    }

    public function test_update_validates_required_fields(): void
    {
        $post = Post::factory()->create();
        $this->put(route('posts.update', $post), [])->assertSessionHasErrors(['title', 'content', 'status']);
    }

    public function test_update_allows_same_slug_on_same_post(): void
    {
        $post = Post::factory()->create(['title' => 'Same Title', 'slug' => 'same-title']);
        $this->put(route('posts.update', $post), ['title' => 'Same Title', 'content' => 'Updated.', 'status' => 'publish'])
             ->assertRedirect(route('posts.index'))->assertSessionHasNoErrors();
    }

    // DESTROY
    public function test_a_post_can_be_deleted(): void
    {
        $post = Post::factory()->create();
        $this->delete(route('posts.destroy', $post))->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_destroy_returns_404_for_nonexistent_post(): void
    {
        $this->delete(route('posts.destroy', 9999))->assertStatus(404);
    }
}
