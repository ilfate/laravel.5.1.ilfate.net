/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Objects = function (game) {
    this.game = game;

    this.activate = function (data) {
        switch(data.action) {
            case 'doorOpen':
                var door = $('.object.id-' + data.object);
                door.addClass('openDoor');
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished();}, 500);
                break;
            case 'doorClose':
                var door = $('.object.id-' + data.object);
                door.removeClass('openDoor');
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished();}, 500);
                break;
        }
    }

};

