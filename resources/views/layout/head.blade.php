@extends('layout.html')

@section('head')
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    @if(!empty($mobileFriendly))
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, minimal-ui">
        <meta name="mobile-web-app-capable" content="yes">
    @endif
    <title>{{ (isset($page_title) ? $page_title : 'Ilfate') }}</title>


    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" >

    <link rel="stylesheet" href="/css/main.css" type="text/css" />

    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>

    {{--Check config at config/header.php--}}
    @foreach(config('header.lists.' . MetaTagsHelper::getPageName() . '.content') as $metaName)
        <meta content="{{ MetaTagsHelper::getTag($metaName) }}" name="{{ $metaName }}">
    @endforeach
    @foreach(config('header.lists.' . MetaTagsHelper::getPageName() . '.property') as $propertyName)
        <meta property="{{ $propertyName }}" content="{{ MetaTagsHelper::getTag($propertyName) }}">
    @endforeach

</head>
<body
        @if(!empty($bodyClass))
            class="{{$bodyClass}}"
        @endif
        >

{{--<script>--}}
    {{--require.config({--}}
        {{--paths: {--}}
            {{--//Comment out this line to go back to loading--}}
            {{--//the non-optimized main.js source file.--}}
            {{--"main": "main-built"--}}
        {{--}--}}
    {{--});--}}
    {{--require(["main"]);--}}
{{--</script>--}}

{{--<script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>--}}
{{--<script type="text/javascript" src="/js/jquery-additional.js"></script>--}}
{{--<script type="text/javascript" src="/js/preloadjs-0.2.0.min.js"></script>--}}
{{--<script type="text/javascript" src="/js/imagesloaded.pkgd.min.js"></script>--}}
{{--<script type="text/javascript" src="/packages/mustache.js"></script>--}}
{{--<script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>--}}
{{--<script type="text/javascript" src="/packages/dropzone.js"></script>--}}


{{--<script type="text/javascript" src="/js/events.js"></script>--}}

{{--<script type="text/javascript" src="/js/index.js"></script>--}}
{{--<script type="text/javascript" src="/js/ajax.js"></script>--}}
{{--<script type="text/javascript" src="/js/modal.js"></script>--}}
{{--<script type="text/javascript" src="/js/form.js"></script>--}}



{{-- GuessSeries --}}
{{--<script type="text/javascript" src="/js/guess/main.js"></script>--}}

{{-- MathEffect --}}
{{--<script type="text/javascript" src="/js/td/compiled.js"></script>--}}

{{--<script type="text/javascript" src="/js/td/game.js"></script>--}}
{{--<script type="text/javascript" src="/js/td/td.facet.js"></script>--}}
{{--<script type="text/javascript" src="/js/td/td.map.js"></script>--}}
{{--<script type="text/javascript" src="/js/td/td.map.config.js"></script>--}}
{{--<script type="text/javascript" src="/js/td/td.unit.js"></script>--}}

{{--<script src="/packages/video-js/video.js"></script>--}}
<script>
//    videojs.options.flash.swf = "/packages/video-js/video-js.swf"
</script>

@yield('layout')

<script src="/js/main.min.js"></script>
<script src="/packages/vue.js"></script>
<script src="/packages/pixi.min.js"></script>
<script src="/packages/vue-material-master/vue-material-0.6.3.js"></script>
<script src="/js/whiteHorde/WhiteHorde.js"></script>
<script src="/js/whiteHorde/interface.js"></script>
<script src="/js/whiteHorde/settlement.js"></script>
<script src="/js/whiteHorde/inventory.js"></script>
<script src="/js/whiteHorde/characterHelper.js"></script>
<script src="/js/whiteHorde/demo.js"></script>
<script src="/js/whiteHorde/game.js"></script>

{{--<script src="/js/cosmos/main.js"></script>--}}
{{--<script src="/js/hex/main.js"></script>--}}
{{--<script src="/packages/mo.min.js"></script>  --}}
{{--<script src="/packages/segment.js"></script> --}}
{{--<script src="/packages/jquery.svg.package-1.5.0/jquery.svg.min.js"></script>--}}
{{--<script src="/packages/jquery.svg.package-1.5.0/jquery.svganim.min.js"></script>--}}

@if (!empty($localDevelopment))
    <input type="hidden" id="isLocalDevelopment" value="1">
    @if (!empty($loadLocalScriptsMS))

        <script src="/js/mageSurvival/main.js"></script>
        <script src="/js/mageSurvival/inventory.js"></script>
        <script src="/js/mageSurvival/animation.js"></script>
        <script src="/js/mageSurvival/attacks.js"></script>
        <script src="/js/mageSurvival/spellbook.js"></script>
        <script src="/js/mageSurvival/spells.js"></script>
        <script src="/js/mageSurvival/spells-fire.js"></script>
        <script src="/js/mageSurvival/spells-water.js"></script>
        <script src="/js/mageSurvival/spells-air.js"></script>
        <script src="/js/mageSurvival/spells-earth.js"></script>
        <script src="/js/mageSurvival/spells-arcane.js"></script>
        <script src="/js/mageSurvival/worlds.js"></script>
        <script src="/js/mageSurvival/objects.js"></script>
        <script src="/js/mageSurvival/units.js"></script>
        <script src="/js/mageSurvival/mage.js"></script>
        <script src="/js/mageSurvival/admin.js"></script>
        <script src="/js/mageSurvival/chat.js"></script>
        <script src="/js/mageSurvival/home.js"></script>
        <script src="/js/mageSurvival/spellcraft.js"></script>
        <script src="/js/mageSurvival/monimations.js"></script>
        <script src="/js/mageSurvival/map-builder.js"></script>
    @endif
@else
 {{-- !!!!!!!!!!!!!!! only live envinment is tracked !!!!!!!!!! --}}
    <script>
        window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
        ga('create', 'UA-55905052-1', 'auto');
        ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>

@endif



</body>

@stop