@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center custom-style">
            <div class="col-md-8 w-70">
                <div class="card">
                    <div class="card-header form_title">Login</div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('login')}}">
                            @csrf
                                @php
                                  if(isset($data)){
                                @endphp
                                     <input type="hidden" name="redirect_url" value="{{ $data['redirect_url']}}">
                                     <input type="hidden" name="user_id" value="{{ $data['user_id']}}">
                                 @php
                                     }
                                 @endphp

                            <div class="row mb-3 d-flex justify-content-center">
                            <!-- <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label> -->

                                <div class="col-md-6 w-70">
                                    <input id="email" placeholder="Email Address" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 d-flex justify-content-center">

                                <div class="col-md-6">
                                    <input id="password" placeholder="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4 d-flex justify-content-between custom-margin">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link p-0"
                                           href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary handle_btn_log">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
