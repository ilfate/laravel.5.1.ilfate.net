@extends('layout.empty')

@section('content')

<div class="loginPage">
    <div class="text-center">
        <div class="logo">login</div>
        <!-- Main Form -->
        <div class="login-form-1">
            <form id="login-form" action="/login" method="POST" class="text-left">
                {!! csrf_field() !!}
                <div class="login-form-main-message"></div>
                <div class="main-login-form">
                    <div class="login-group">
                        <div class="form-group">
                            <label for="lg_email" class="sr-only">Email</label>
                            <input type="text" class="form-control" id="lg_email" name="email" placeholder="email">
                        </div>
                        <div class="form-group">
                            <label for="lg_password" class="sr-only">Password</label>
                            <input type="password" class="form-control" id="lg_password" name="password" placeholder="password">
                        </div>
                    </div>
                    <button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
                </div>
                <div class="etc-login-form">
                    {{--<p>forgot your password? <a href="#">click here</a></p>--}}
                    <p>new user? <a href="/register">create new account</a></p>
                </div>
            </form>
        </div>
        <!-- end:Main Form -->
    </div>


</div>

@include('blocks.gdpr')

@stop
