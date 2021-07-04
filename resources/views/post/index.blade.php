<x-app-layout>
    <x-slot name="header">
        <h2 class="title">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="columns">
        @foreach($posts as $post)
            <div class="column is-4">
                <div class="card mt-2">
                    <div class="card-header-title title is-3 mb-1">
                        {{$post->title}}
                    </div>
                    <div class="card-content pt-1">
                        <div class="content-summary mb-2">
                            {{$post->content}}
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
                    </div>
                    @if(\Illuminate\Support\Facades\Auth::check() && $post->user_id === \Illuminate\Support\Facades\Auth::user()->id)
                        edit
                        delete
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>