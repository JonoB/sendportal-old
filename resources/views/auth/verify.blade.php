@extends('auth.template')

@section('title', __('Verify Your Email Address'))

@section('content')
    <div class="container min-h-screen">
        <div class="row h-full d-flex justify-center items-center min-h-screen">
            <div class="col-md-6">
                <div class="login-container">
                    <div class="login-body">
                        <h1>{{ __('Verify Your Email Address') }}</h1>

                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
