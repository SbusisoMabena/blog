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

    public function test_delete_will_redirect_to_the_main_page_with_a_message_when_successful()
    {
        $this->seed();
        $user = User::all()->first();
        $post = $user->post->first();

        $response = $this->actingAs($user)
            ->delete("post/{$post->slug}");

        $response->assertSessionHas('success-message', "Your post has been deleted");
    }

    public function test_delete_does_not_allow_a_user_to_delete_a_post_that_does_not_belong_to_them()
    {
        $this->seed();
        $user = User::all()->first();
        $post = $user->post->first();

        $user2 = User::factory()->create();

        $response = $this->actingAs($user2)
            ->delete("post/{$post->slug}");

        $response->assertStatus(403);
    }

    public function test_delete_redirects_the_user_when_they_are_trying_to_delete_a_post_that_does_not_exist()
    {
        $this->withoutExceptionHandling();
        $this->seed();
        $user = User::all()->first();

        $response = $this->actingAs($user)
            ->delete("post/does-not-exist}");

        $response->assertRedirect('/');
    }

    public function test_edit_does_not_allow_a_user_to_edit_a_post_that_does_not_belong_to_them()
    {
        $this->seed();
        $user = User::all()->first();
        $post = $user->post->first();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user2)
            ->patch("/post/{$post->slug}",[
                "title"=>"updated title",
                "content"=>"updated content"
            ]);

        $response->assertStatus(403);
    }

    public function test_edit_creates_a_post_that_does_not_exist()
    {
        $this->seed();
        $user = User::all()->first();

        $response = $this->actingAs($user)
            ->patch("/post/the-post-does-not-exist",[
                "title"=>"updated title",
                "content"=>"updated content"
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHas(['message'=>"Your post has been created"]);
    }
}
