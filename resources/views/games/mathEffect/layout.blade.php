@extends('layout.head')

@section('layout')

@include('menu')

<div class="math-effect-container">
    <div class="container main">
        <div class="row">
            <div class="col-md-8">
                <div class="main-content-well well well-small ">
                    @yield('content')
                </div>
            </div>

            <div class="col-md-4 sidebar">
                @include('sidebar')
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="_token" id="laravel-token" value="{{ csrf_token() }}">

 {{--Go to www.addthis.com/dashboard to customize your tools --}}
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54ddd47f2fc16fe4" async="async"></script>

@stop

