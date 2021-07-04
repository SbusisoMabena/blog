<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_post_renders_the_screen_when_the_user_is_authenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get('/post/create');

        $response->assertViewIs("post.create");
        $response->assertStatus(200);
    }

    public function test_create_post_redirects_an_unauthenticated_user_to_the_login_page()
    {
        $response = $this->get('/post/create');

        $response->assertRedirect('/login');
    }

    public function test_submitting_a_valid_post_creates_the_post_and_redirects_to_the_main_page()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/post',[
                "title"=>"test post",
                "content"=>"test post content"
            ]);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();
    }

    public function test_submitting_an_invalid_post_redirects_the_user_to_the_create_post_page_with_an_error()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/post',[
                "title"=>"",
                "content"=>"test post content"
            ]);
        $response->assertSessionHasErrors(['title']);
    }
}
