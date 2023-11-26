@include ('template-parts.head')
<div class="page-wrapper login-page">
    <header>
        <div class="img">
            <img height="50px" src="{{ URL::to('/') }}/images/logo-sima-whiteVersion.svg"
                alt="Logo" class="light-logo">
            <img height="50px" src="{{ URL::to('/') }}/images/logo-lektiko-whiteVersion.svg"
                class="light-logo" alt="Text logo">
        </div>
    </header>
    <main>
        <div class="bg-image"></div>
        <div class="container-fluid login-content">
            <div class="card shadowed" style="width: 350px;padding: 20px;margin: 0px auto">
                <h1>{{ __('Σύνδεση') }}</h1>
                <form action="{{ route('login', app()->getLocale()) }}" method="post">
                    {{ csrf_field() }}
                    <label for="email">{{ __('E-Mail ή Username') }}</label>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email"
                        autofocus>
                    @error ('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label
                        for="password" class="mt-3">{{ __('Κωδικός πρόσβασης') }}</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password">
                        <div class="input-group-prepend">
                            <span id="trigger-password-view" class="input-group-text" data-status="disabled"><em class="fa fa-eye"></em></span>
                        </div>
                    </div>

                    @error ('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br />
                    <label for="remember"><input type="checkbox" id="remember" />
                        {{ __('Κράτησε με συνδεδεμένο') }}</label>
                    <br />
                    <input type="submit" class="submit btn btn-success" value="Σύνδεση" name="remember" />
                </form>
            </div>
        </div>
    </main>
    @include ('template-parts.footer')
</div>
