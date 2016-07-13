@extends('games.mageSurvival.layout')

@section('content')

<div class="content">
    <div class="page-controlls">
        <a href="/Spellcraft">Back to game</a>
    </div>
    <div class="page-header">
        <h1>Spellcraft <br><small style="padding-left: 5rem;">and all about it</small></h1>
    </div>

    <div class="text-block">
        Hi. My name is Ilya. Thanks for playing my game! Creating game is my hobby and if would like to know I can tell you all about creating this game! Spellcraft is pure web game. On frontend side I used JS + CSS + lots of SVGs for animations. All logic is done on backend side with PHP. This way all your progress is saved on every step. It took me around 6 month to bring this game to life. I hope you like it. With your support this game could grow. Plz let me know what you thing about it on Reddit. Or feel free to contact me via email at ilfate@gmail.com
    </div>
    <div class="text-block">
        <table class="table">
            <caption>This game was created with help of next tools:</caption>
            <tbody>
                <tr>
                    <td><a href="http://game-icons.net/">game-icons.net</a></td>
                    <td>A great set of icons that I used everywhere in the game. Without them creating this game would not be possible. Thanks alot!</td>
                </tr>
                <tr>
                    <td><a href="http://lmgonzalves.github.io/segment/">Segment.js</a></td>
                    <td>A great lib for svg animations.</td>
                </tr>
                <tr>
                    <td><a href="http://keith-wood.name/svg.html">jquery svg plugin</a></td>
                    <td>A plugin for svg animations</td>
                </tr>
                <tr>
                    <td><a href="http://mojs.io/">Mo.js</a></td>
                    <td>A little library that I used for some custom animations.</td>
                </tr>
                <tr>
                    <td><a href="http://opengameart.org/content/platformer-grass-tileset">Art of PlatForge project</a></td>
                    <td>Thanks to the artists: Summer Thaxton and Hannah Cohan. I used a tree image for that tileset.</td>
                </tr>
                <tr>
                    <td><a href="https://kenney.itch.io/">Kenney`s 2D assets</a></td>
                    <td>I used some assets as an inspiration to create my SVGs.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@stop
