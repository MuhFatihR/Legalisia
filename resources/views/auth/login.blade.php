<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Login | Compnet</title>
    @include('inc.metatag')
    @include('inc.styles')
    <style>
        .btn i { margin-right: 15px;}
        .btn-primary {background-color: #034ea2; border-color: #034ea2;}
        .btn-primary:hover {background-color: #2f90fb; border-color: #2f90fb;}
        .btn-microsoft {background-color: #01a9ac; color: #FFFFFF;}
        .btn-microsoft:hover {background-color: #01dbdf; color: #FFFFFF;}
        .login-block { background: url({{ asset('files/assets/images/background.png') }}) no-repeat; background-size: cover; }
    </style>
</head>

<body class="fix-menu">
    @include('inc.theme-loader')
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container-fluid">
            <!-- Row starts -->
            <div class="row">
                <!-- Col-sm-12 starts -->
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <form method="POST" action="{{ url('login') }}" class="md-float-material form-material">
                        @csrf
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12 text-center">
                                        <img src="{{asset('/files/assets/images/compnet-logo.png')}}" alt="logo.png" width="200px">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-grid">
                                            <a href="{{ route('connect') }}" class="btn btn-microsoft m-b-20">
                                                <i class="fa-brands fa-microsoft"></i>Sign in with Azure AD
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted text-center p-b-5">Or sign in with your network account</p>
                                @if (Session::has('loginError'))
                                <div class="alert alert-dismissible alert-danger icons-alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <p><strong>Failed!</strong> {{ Session::get('loginError') }}</p>
                                </div>
                                @endif
                                <div class="mb-3 form-primary">
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required="" autocomplete="email" placeholder="Email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="mb-3 form-primary">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required="" placeholder="Password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-md waves-effect text-center m-b-20">LOGIN</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end of form -->
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    @include('inc/scripts')
</body>
</html>