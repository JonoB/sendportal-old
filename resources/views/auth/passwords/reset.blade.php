@extends('common.template')

@section('content')
    <div class="container min-h-screen">
        <div class="row h-full d-flex justify-center items-center min-h-screen">
            <div class="col-md-4">
                <div class="login-container">
                    <div class="login-body">
                        <h1>SendPortal</h1>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row">
                                <label for="email"
                                       class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <input id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email" value="{{ old('email') }}" placeholder="example@email.com" required
                                       autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-form-label text-md-right">{{ __('Password') }}</label>

                                <input id="password" type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" placeholder="**********" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                       class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" placeholder="**********" required>
                            </div>

                            <div class="form-group row mb-0 text-center login-row">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection