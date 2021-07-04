<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PostTest extends TestCase
{
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
        $user = User::all()->first();

        $response = $this->actingAs($user)
            ->get('/post');
        $response->assertSeeText("Log out")
            ->assertSeeText($user->name);
    }

    public function test_posts_screen_has_the_seeded_post()
    {
        $response = $this->get('/post');

        $response->assertSeeText("Test Post")
            ->assertDontSeeText("edit");
    }

    public function test_posts_screen_has_edit_and_detele_options_when_the_user_is_logged_in_and_they_own_the_post()
    {
        $user = User::all()->first();

        $request = $this->actingAs($user)->get('/post');
        $request->assertSeeText('Test Post')
            ->assertSeeText('edit')
            ->assertSeeText('delete');
    }


}
