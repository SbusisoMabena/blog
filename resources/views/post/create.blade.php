<x-app-layout>
    <x-slot name="header">
        <h2 class="title">
            {{ __('Create Post') }}
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
                <div class="field">
                    <label class="label">Post Title</label>
                    <div class="control">
                        <input class="input" name="title" type="text">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Post Content</label>
                    <div class="control">
                        <textarea class="textarea" name="content"></textarea>
                    </div>
                </div>
                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-link">Submit Post</button>
                    </div>
                    <div class="control">
                        <button type="reset" class="button is-link is-light">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
