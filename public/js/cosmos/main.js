/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */


function Cosmos () {

}
Cosmos = new Cosmos();

$(document).ready(function() {
    if ($('body.cosmos').length) {
        Cosmos.Game = new Cosmos.Game();
    }
});


Cosmos.Game = function () {
    this.color = {
        'blue' : '#428BCA',
        'green' : '#069E2D',
        'yellow' : '#FFD416',
        'red' : '#F21616',
        'orange' : '#EF8354',
        'black' : '#584D3D',
        'white' : '#FFFFFF'
    };
	this.nextQuestion = [];
	this.questionNumber = 1;
    this.queue = {};
    this.ship;



    this.renderShip = function() {
        this.ship.render($('.ship-container'));

    }

    this.createShipFromJson = function(data) {
        this.ship = new Cosmos.Ship(data);
    }

}

Cosmos.Ship = function(data) {
    this.id = 0;
    this.modules = [];

    if (data.modules != undefined) {
        for (var i in data.modules) {
            this.modules[i] = new Cosmos.Module(data.modules[i]);
        }
    }

    this.render = function (target) {
        for (var i in this.modules) {
            this.modules[i].render(target);
        }
    }
}

Cosmos.Module = function(data) {
    this.id = data.id;
    this.x = data.x;
    this.y = data.y;
    this.type = 'normal';
    this.cells = [];

    if (data.cells != undefined) {
        for (var i in data.cells) {
            this.cells[i] = new Cosmos.Cell(data.cells[i]);
        }
    }

    this.render = function(target) {
        for (var key in this.cells) {
            this.cells[key].render(target);

        }
    }
}

Cosmos.Cell = function(data) {
    this.x = data.x;
    this.y = data.y;
    this.type = 'normal';
    this.doors = data.doors;

    this.render = function(target) {
        var temaplate = $('#template-cell').html();
        Mustache.parse(temaplate);
        var doors = '';
        var rendered = Mustache.render(temaplate, {
            'doors' : this.doors,
            'x' : this.x,
            'y' : this.y
        });
        var obj = $(rendered);
        $('.turn-area').append(obj);
        target.append(obj);
    }
}


function pasteText(text, el, options) {
    if (options && options.duration !== undefined) {
        if (text.length == 1) {
            var time = options.duration;
        } else {
            var time = parseInt((options.duration / text.length)* (rand(8,12)/10));
            options.duration -= time;
        }
    } else {
        var time = rand(10,80);
    }
    var letter = text.substr(0, 1);
    var rest = text.substr(1);
    el.append(letter);
    if (text.length > 1) {
        setTimeout(function(){pasteText(rest, el, options);}, time);
    } else {
        if (options && options.queue !== undefined) {
            var data = options.queue[0];
            options.queue.shift();

            if (data.options == undefined) {
                data.options = {};
            }
            if (options.queue.length > 0) {
                data.options.queue = options.queue;
            }
            pasteText(data.text, data.el, data.options);
        }
    }
}