<x-app-layout>
    <x-slot name="header">
        <h2 class="title">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="columns">
        <div class="column is-6 is-offset-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <article class="message is-danger">
                                <div class="message-header">
                                    <p>{{$error}}</p>
                                </div>
                            </article>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="." method="POST">
                @csrf
                @method("PUT")
                <div class="field">
                    <label class="label">Post Title</label>
                    <div class="control">
                        <input class="input" name="title" type="text" value="{{$post->title}}">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Post Content</label>
                    <div class="control">
                        <textarea class="textarea" name="content">{{$post->content}}</textarea>
                    </div>
                </div>
                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-link">Submit Post</button>
                    </div>
                    <div class="control">
                        <button type="button" class="button is-link is-light" onclick="window.history.back()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
