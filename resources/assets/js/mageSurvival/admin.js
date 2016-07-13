/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Admin = function (game) {
    this.game = game;
    this.isEnabled = false;
    this.actions = {};
    this.currentAction = {};
    this.actionNumber = 0;
    this.timeToLoadAjax = 1000;
    this.timeToLoadAjax2 = 5000;
    this.timeToLoadAjax3 = 30000;
    this.failedActions = 0;

    this.init = function() {
        this.isEnabled = true;
        $('.dead-mage').on('click', function() {
            $(this).next().slideToggle();
            if ($(this).hasClass('active')) {
                $(this).find('i').animateRotate(180, 0, 200, 'easeInOutQuad');
                $(this).removeClass('active');
            } else {
                // info($(this).find('svg'));
                $(this).find('i').animateRotate(0, 180, 400, 'easeInOutQuad');
                $(this).addClass('active');
            }
        });
    };
    
    this.start = function () {
        this.actions = this.game.rawData.loggedActions;
        if (!this.actions || this.actions.length == 0) {
            info ('there was no actions...');
            return;
        }
        this.performAction();
    };

    this.getNextAction = function() {
        var action = this.actions[0];
        this.actions = this.actions.slice(1);
        this.currentAction = action;
        if (action) this.actionNumber ++;
        return action;
    };
    
    this.performAction = function() {
        var action = this.getNextAction();
        if (!action) {
            info ('Preloaded actions are finished');
            setTimeout(function(){ MageS.Game.admin.loadActions();}, MageS.Game.admin.timeToLoadAjax);
            return;
        }

        action.data.fake = true;
        if (action.action == 'move') {
            var data = JSON.parse(action.data);
            switch (data.d) {
                case '0': action.action = 'move-up'; break;
                case '1': action.action = 'move-right'; break;
                case '2': action.action = 'move-down'; break;
                case '3': action.action = 'move-left'; break;
            }
        }
        this.game.action(action.action, action.data);
        switch(action.action) {
            case 'spell':
                var dataObject = JSON.parse(action.data);
                var spellName = $('.spell.id-' + dataObject.id).data('spell');
                MageS.Game.spells.startCast(spellName);
                break;
        }
        setTimeout(function(){
            MageS.Game.admin.finishAction();
        }, 150);
    };
    
    this.finishAction = function () {
        this.game.callback(this.currentAction.result);
    };

    this.actionEnded = function () {
        if (!this.isEnabled) return;

        setTimeout(function(){
            MageS.Game.admin.performAction();
        }, 300);
    };

    this.loadActions = function() {
        var url = '/Spellcraft/admin/getActions/' + this.game.rawData.userId + '/' + this.game.rawData.pageTime;
        Ajax.json(url, {
             data: 'action=' + MageS.Game.admin.actionNumber,
             callBack : function(data){
                MageS.Game.admin.callback(data)
             }
        });
    };

    this.callback = function(data) {
        info(data);
        if (data && data.actions !== undefined && data.actions.length > 0) {
            this.actions = data;
            this.failedActions = 0;
            this.performAction();
        } else {
            if (this.game.rawData['public'] !== undefined) {
                window.location = '/Spellcraft';
            }
            if (data.thisWasLast !== undefined) {
                MageS.Game.chat.dialogMessage({'targetX':0, 'targetY':0, 'message':'This was last action on this page'});
                return;
            }
            this.failedActions++;
            var time = MageS.Game.admin.timeToLoadAjax2;
            if (this.failedActions > 5) {
                time = MageS.Game.admin.timeToLoadAjax3;
            }
            setTimeout(function(){ MageS.Game.admin.loadActions();}, time);
        }
    }
};

