@extends('layout.empty')

@section('content')

<div class="loginPage">
    <div class="text-center">
        <div class="logo">register</div>
        <!-- Main Form -->
        <div class="login-form-1">
            <form id="register-form" method="POST" action="/register" class="text-left">
                {!! csrf_field() !!}

                @if (!empty($errors) && $errors->count())
                    <div class="login-form-main-message show">
                        <?php $errorMessages = $errors->all(); ?>
                            @foreach ($errorMessages as $error)
                                <p>{{$error}}</p>
                            @endforeach
                    </div>
                @endif
                <div class="main-login-form">
                    <div class="login-group">
                        <div class="form-group">
                            <label for="reg_email" class="sr-only">Email address</label>
                            <input type="text" class="form-control" value="{{isset($formDefaults['email'])?$formDefaults['email']:''}}" id="reg_email" name="email" placeholder="email">
                        </div>
                        <div class="form-group">
                            <label for="reg_password" class="sr-only">Password</label>
                            <input type="password" class="form-control" id="reg_password" name="password" placeholder="password">
                        </div>
                        <div class="form-group">
                            <label for="reg_password_confirm" class="sr-only">Password Confirm</label>
                            <input type="password" class="form-control" id="reg_password_confirm" name="password_confirm" placeholder="confirm password">
                        </div>

                        <div class="form-group">
                            <label for="reg_name" class="sr-only">Name</label>
                            <input type="text" class="form-control" value="{{isset($formDefaults['name'])?$formDefaults['name']:''}}" id="reg_name" name="name" placeholder="name">
                        </div>
                    </div>
                    <button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
                </div>
                <div class="etc-login-form">
                    <p>already have an account? <a href="/login">login here</a></p>
                </div>
            </form>
        </div>
        <!-- end:Main Form -->
    </div>
</div>

@stop
