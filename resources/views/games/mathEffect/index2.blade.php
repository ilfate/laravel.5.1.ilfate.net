@extends('layout.empty')

@section('additional-js')
    <script src="/js/bundle.js"></script>
@endsection



@section('content')

    <div id="react-app">
        <h1>Math Effect</h1>
    </div>

@include('blocks.gdpr')

    <input type="hidden" name="checkKey" id="checkKey" value="{{ $checkKey }}" />
    <input type="hidden" name="userName" id="userName" value="{{ empty($userName) ? 'none' : $userName}}" />

    <div class="me-facebook-modal">
        <div class="addthis_sharing_toolbox" data-url="http://ilfate.net/MathEffect" data-title="MathEffect"></div>
    </div>

    <script data-cookieconsent="statistics" type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54ddd47f2fc16fe4" async="async"></script>

@endsection
