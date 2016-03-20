
<div class="mage-profile">
    <div class="health-bar">
        <div class="progress">
            <div class="progress-bar progress-bar-success" style="width: {{$viewData['game']['mage']['health']}}%">
                <span>{{$viewData['game']['mage']['health']}}HP</span>
            </div>
            <div class="progress-bar progress-bar-warning progress-bar-striped"
                 style="width: {{isset($viewData['game']['mage']['armor']) ? $viewData['game']['mage']['armor'] : ''}}%"
            >
                <span>{{isset($viewData['game']['mage']['armor']) ? $viewData['game']['mage']['armor'] : ''}}</span>
            </div>
        </div>
    </div>
</div>

