

<script id="stats-overlay" type="x-tmpl-mustache">
    <div class="stats-overlay">
        <a class="close-button"><span class="badge">X</span></a>
        <div class="page-header">
          <h1>Leaderboard <small>how good you are?</small></h1>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Top today</div>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Waves Survived</th>
                </tr>
                </thead>
                <tbody>
                {{#stats.todayLogs}}
                    <tr>
                        <td>{{num}}</td>
                        <td>{{name}}</td>
                        <td><b>{{waves}}</b></td>
                    </tr>
                {{/stats.todayLogs}}
                </tbody>
            </table>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">Top 10 games</div>
          <table class="table">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Waves</th>
                </tr>
              </thead>
              <tbody>
                {{#stats.topLogs}}
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{name}}</td>
                        <td><b>{{waves}}</b></td>
                    </tr>
                {{/stats.topLogs}}
              </tbody>
          </table>
        </div>

        <div class="panel panel-default">
          <div class="panel-body">
            Total games played
          </div>
          <div class="panel-footer">{{stats.totalGames}}</div>
        </div>

        <div class="panel panel-default">
          <div class="panel-body">
            Average turns survived per game
          </div>
          <div class="panel-footer">{{stats.avrTurns}}</div>
        </div>

        <div class="panel panel-default">
          <div class="panel-body">
            Number of players
          </div>
          <div class="panel-footer">{{stats.users}}</div>
        </div>
    </div>
</script>