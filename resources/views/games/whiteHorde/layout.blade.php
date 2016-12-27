@extends('layout.head')

@section('layout')

<div class="content-container">
        @yield('content')
</div>



<input type="hidden" name="_token" id="laravel-token" value="{{ csrf_token() }}">



    @include('games.whiteHorde.templates')
@if (!empty($viewData))
    <script> whiteHordeData = {!!json_encode($viewData)!!} </script>
@endif
{{--<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-583d4614697af144"></script>--}}
@stop

