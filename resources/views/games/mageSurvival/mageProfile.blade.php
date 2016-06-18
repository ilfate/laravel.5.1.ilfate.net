
<div class="mage-profile">
    <div class="health-bar">
        <div class="progress">
            <div class="progress-bar progress-bar-success"
                 style="width: {{$viewData['game']['mage']['health']}}%"
            >
                <span class="health-value">{{$viewData['game']['mage']['health']}}HP</span>
            </div>
            <div class="progress-bar progress-bar-warning progress-bar-striped"
                 style="width: {{isset($viewData['game']['mage']['armor']) ? $viewData['game']['mage']['armor'] : 0}}%"
            >
                <span class="armor-value">{{isset($viewData['game']['mage']['armor']) ? $viewData['game']['mage']['armor'] : ''}}</span>
            </div>
        </div>
    </div>
    <div class="health-mobile-info">
        <div class="health">

            <div class="svg svg-replace normal" data-color="red" data-svg="icon-heart">
                <svg class="svg-icon" viewBox="0 0 512 512" >
                </svg>
            </div>
            <div class="svg svg-replace cover" data-color="grey" data-svg="icon-heart">
                <svg class="svg-icon" viewBox="0 0 512 512" >
                </svg>
            </div>
            <span class="value">{{$viewData['game']['mage']['health']}}</span>
        </div>
        <div class="armor">
            <div class="svg svg-replace" data-color="brown" data-svg="icon-shield">
                <svg class="svg-icon" viewBox="0 0 512 512" >
                </svg>
                <span class="value">{{isset($viewData['game']['mage']['armor']) ? $viewData['game']['mage']['armor'] : ''}}</span>
            </div>
        </div>
    </div>
</div>

