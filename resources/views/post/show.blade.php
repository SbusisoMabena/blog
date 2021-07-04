<x-app-layout>
    <x-slot name="header">
        <h2 class="title">
            {{ $post->title }}
        </h2>
        <h3 class="subtitle">{{$post->user->name}}</h3>
        <time datetime="{{$post->updated_at}}">{{date("d F Y", strtotime($post->updated_at))}}</time>
    </x-slot>

    <div class="columns mt-2">
        <div class="column is-8 is-offset-2">
            <p>{{$post->content}}</p>
        </div>
    </div>
</x-app-layout>
