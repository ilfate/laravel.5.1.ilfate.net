@extends('layout.head')

@section('layout')


{{--<div class="game-body">--}}
    {{--<div class="container main">--}}
        @yield('content')
    {{--</div>--}}
{{--</div>--}}


<input type="hidden" name="_token" id="laravel-token" value="{{ csrf_token() }}">

{{--<div id="fb-root"></div>--}}
{{--<script>(function(d, s, id) {--}}
        {{--var js, fjs = d.getElementsByTagName(s)[0];--}}
        {{--if (d.getElementById(id)) return;--}}
        {{--js = d.createElement(s); js.id = id;--}}
        {{--js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=243940452354382&version=v2.0";--}}
        {{--fjs.parentNode.insertBefore(js, fjs);--}}
    {{--}(document, 'script', 'facebook-jssdk'));</script>--}}
{{--<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>--}}
{{--<div class="facebook-like-hidden">--}}
    {{--<div class="fb-like" data-href="http://ilfate.net/GuessSeries" data-layout="box_count" data-action="like" data-show-faces="false" data-share="true"></div>--}}
    {{--<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://ilfate.net/GuessSeries">Tweet</a>--}}

{{--</div>--}}

    @include('games.td.templates')
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54ddd47f2fc16fe4"></script>
@stop

