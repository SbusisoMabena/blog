<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_screen_can_be_rendered()
    {
        $response = $this->get('/post');

        $response->assertStatus(200);
    }

    public function test_posts_screen_has_login_and_signup_button_when_the_user_is_not_authenticated()
    {
        $response = $this->get('/post');
        $response->assertSeeText("Sign up")
            ->assertSeeText("Log in");
    }

    public function test_posts_screen_has_the_users_name_and_logout_button_when_the_user_is_authenticated()
    {
        $this->seed();
        $user = User::all()->first();

        $response = $this->actingAs($user)
            ->get('/post');
        $response->assertSeeText("Log out")
            ->assertSeeText($user->name);
    }

    public function test_posts_screen_has_the_seeded_post()
    {
        $this->seed();
        $post = Post::all()->first();
        $response = $this->get('/post');

        $response->assertSeeText($post->title);
    }

    public function test_posts_screen_has_edit_and_detele_options_when_the_user_is_logged_in_and_they_own_the_post()
    {
        $this->seed();
        $user = User::all()->first();
        $post = $user->post->first();

        $response = $this->actingAs($user)->get('/post');
        $response->assertSeeText($post->title)
            ->assertSeeText('edit')
            ->assertSeeText('delete');
    }

    public function test_edit_post_screen_can_be_rendered()
    {
        $this->seed();
        $user = User::all()->first();
        $post = $user->post->first();

        $response = $this->actingAs($user)
            ->get("/post/{$post->slug}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('post.edit');
    }

    public function test_edit_post_redirects_to_a_404_page_when_the_slug_is_invalid()
    {
        $this->seed();
        $user = User::all()->first();

        $response = $this->actingAs($user)
            ->get("/post/invalid-slug/edit");

        $response->assertStatus(404);
    }

    public function test_edit_post_redirects_to_the_main_page_when_the_post_has_been_updated_successfully()
    {
        $this->seed();
        $user = User::all()->first();
        $post = $user->post->first();

        $response = $this->actingAs($user)
            ->patch("/post/{$post->slug}",[
               "title"=>"updated title",
               "content"=>"updated content"
            ]);

        $response->assertRedirect('/');

    }

    public function test_edit_post_returns_errors_when_the_submitted_post_is_invalid()
    {
        $this->seed();
        $user = User::all()->first();
        $post = $user->post->first();

        $response = $this->actingAs($user)
            ->patch("/post/{$post->slug}",[
                "title"=>null,
                "content"=>""
            ]);
        $response->assertSessionHasErrors(['title','content']);
    }
}
