<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use RefreshDatabase;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('post.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("post.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|unique:posts|max:255",
            "content" => "required"
        ]);

        $title = $request['title'];
        $content = $request['content'];
        $slug = Str::slug($title, '-');
        $userId = Auth::user()->id;

        $post = Post::create([
            'title' => $title,
            'content' => $content,
            'slug' => $slug,
            'user_id' => $userId
        ]);

        return redirect('/')->with("message", "Your post has been created");
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $post = Post::where("slug", $slug)->firstOrFail();

        return view('post.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $post = Post::where("slug", $slug)->firstOrFail();
        return view('post.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $slug)
    {
        $validatedRequest = $this->validate($request,
            [
                'title' => 'required|max:255',
                'content' => 'required'
            ]
        );
        $post = Post::where('slug', $slug)->first();
        if(!isset($post))
        {
            $title = $validatedRequest['title'];
            $content = $validatedRequest['content'];
            $slug = Str::slug($title, '-');
            $userId = Auth::user()->id;

            $post = Post::create([
                'title' => $title,
                'content' => $content,
                'slug' => $slug,
                'user_id' => $userId
            ]);

            return redirect('/')->with("message", "Your post has been created");
        }

        if ($post->user_id === Auth::user()->id) {
            $post->title = $validatedRequest['title'];
            $post->slug = Str::slug($validatedRequest['title']);
            $post->content = $validatedRequest['content'];

            $post->save();
            $post->refresh();

            return redirect('/')->with("message", "Your post has been updated");
        }

        return abort(403, "You are not authorised to edit this post");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug)
    {
        $post = Post::where('slug', $slug)->first();
        if (!isset($post)) {
            return redirect('/');
        }
        if ($post->user_id === Auth::user()->id) {
            $post->delete();
            return redirect('/')->with('success-message', "Your post has been deleted");
        }

        abort(403, "Unauthorised delete");
    }
}
