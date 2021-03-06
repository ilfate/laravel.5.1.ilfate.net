@extends('layout.head')

@section('layout')


<div class="guess-body">
    <div class="top-navigation">
        <a href="{{ @action('GamesController@index') }}">Back to games</a>
    </div>
    <div class="container main">
        <div class="row">
            <div class="col-md-9 game-area">
                @yield('content')
            </div>
            <div class="col-md-3 sidebar-col">
                @yield('sidebar')
            </div>
        </div>
    </div>
    @include('blocks.gdpr')
</div>


<input type="hidden" name="_token" id="laravel-token" value="{{ csrf_token() }}">

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=243940452354382&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<div class="facebook-like-hidden">
    <div class="fb-like" data-href="http://ilfate.net/GuessSeries" data-layout="box_count" data-action="like" data-show-faces="false" data-share="true"></div>
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://ilfate.net/GuessSeries">Tweet</a>

</div>


@stop

