<nav x-data="{open: false}" class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{route('dashboard')}}">
            <P class="title">LaraBlog</P>
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-end">
            @auth
                <div class="navbar-item">
                    {{\Illuminate\Support\Facades\Auth::user()->name}}
                </div>
                <div class="navbar-item">
                    <form method="POST" action="{{route('logout')}}">
                        @csrf
                        <button type="submit" class="button is-light">
                            Log out
                        </button>
                    </form>
                </div>
            @else
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-primary">
                            <strong>Sign up</strong>
                        </a>
                        <a class="button is-light" href="{{route('login')}}">
                            Log in
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</nav>