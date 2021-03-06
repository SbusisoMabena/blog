<x-app-layout>
    <x-slot name="header">
        <h2 class="title">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <a href="post/create" class="button is-primary mt-2">Create Post</a>
    @if(\Illuminate\Support\Facades\Session::has('success-message'))
        <article class="message is-primary mt-2">
            <div class="message-header">
                <p>{{\Illuminate\Support\Facades\Session::get('success-message')}}</p>
            </div>
        </article>
    @endif
    <div class="columns is-multiline">
        @foreach($posts as $post)
            <div class="column is-4">
                <div class="card mt-2">
                    <div class="card-header-title title is-3 mb-1">
                        {{$post->title}}
                    </div>
                    <div class="card-content pt-1">
                        <div class="content-summary mb-2">
                            {{Str::words($post->content,40)}}
                        </div>
                        <div class="media-content">
                            <div class="columns">
                                <div class="column">
                                    <p class="title is-5">{{$post->user->name}}</p>
                                </div>
                                <div class="column">
                                    <time datetime="{{$post->updated_at}}">{{date("d F Y", strtotime($post->updated_at))}}</time>
                                </div>
                            </div>
                            <a href="{{"post/".$post->slug}}">read more...</a>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::check() && $post->user_id === \Illuminate\Support\Facades\Auth::user()->id)
                            <div class="columns">
                                <div class="column">
                                    <a href="post/{{$post->slug}}/edit">edit</a>
                                </div>
                                <div class=" column">
                                    <form method="POST" action="post/{{$post->slug}}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="is-link">delete</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>