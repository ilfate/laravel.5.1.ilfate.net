/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Admin = function (game) {
    this.game = game;
    this.isEnabled = false;
    this.actions = {};
    this.currentAction = {};
    this.actionNumber = 0;

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
            setTimeout(function(){ MageS.Game.admin.loadActions();}, 1500);
            return;
        }
        action.data.fake = true;
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
        info('ACTION ENDED');
        if (!this.isEnabled) return;

        setTimeout(function(){
            MageS.Game.admin.performAction();
        }, 300);
    };

    this.loadActions = function() {
        Ajax.json('/Spellcraft/admin/getActions/' + this.game.rawData.userId + '/' + this.game.rawData.pageTime, {
            data: 'action=' + this.actionNumber ,
            callBack : function(data){ MageS.Game.admin.callback(data) }
        });
    };

    this.callback = function(data) {
        info(data);
        if (data && data.length > 0) {
            this.actions = data;
            this.performAction();
        } else {
            setTimeout(function(){ MageS.Game.admin.loadActions();}, 3000);
        }
    }
};

