<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_post_screen_can_be_render()
    {
        $this->seed();
        $user = User::all()->first();

        $response = $this->get("post/{$user->post->first()->slug}");

        $response->assertStatus(200);
    }
}
