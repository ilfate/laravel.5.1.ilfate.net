@extends('games.mageSurvival.layout')

@section('content')

<div class="admin-view">

    <div class="player-mage-list">
        @if(count($viewData['userLogs']))
            @foreach($viewData['userLogs'] as $user)
                @if (!empty($user['pages']))

                <div class="dead-mage">
                    <div class="text">
                        <span class="name">{{$user['name']}} | {{$user['email']}}</span>
                    </div>

                </div>
                <div class="hidden-mage-info">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>time</th>
                            <th>actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user['pages'] as $page)
                            <tr>
                                <td>
                                    <a href="/Spellcraft/admin/page/{{$user['id']}}/{{$page['pageTime']}}">{{$page['time']}}</a>
                                </td>
                                <td>{{$page['actions']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            @endforeach
        @else
            <div class="mage">
                There was no users.... :(
            </div>
        @endif
    </div>

</div>
<input type="hidden" id="game-status" value="admin" />
@stop
