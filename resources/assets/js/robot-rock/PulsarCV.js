/* *
	*
	* made by ilfate.=/[-7
	* started at 27.05.11
	*
	*/
if ($('body.robot-rock').length > 0) {

	$(document).ready(function () {
		var map = [[0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 2, 2, 2, 2, 2, 1, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 2, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 2, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 2, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 2, 2, 1, 2, 2, 2, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 2, 0, 0, 2, 2, 2, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 2, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0], [2, 2, 2, 1, 2, 2, 2, 1, 1, 1, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0], [0, 1, 2, 1, 1, 2, 2, 1, 2, 2, 2, 0, 0, 2, 0, 0, 0, 0, 0, 0], [0, 2, 2, 1, 2, 1, 2, 1, 2, 2, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0], [0, 1, 0, 1, 2, 1, 2, 1, 2, 2, 2, 2, 0, 2, 0, 0, 0, 0, 0, 0], [0, 2, 2, 2, 2, 2, 2, 1, 2, 1, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0], [0, 2, 0, 2, 2, 1, 1, 1, 2, 2, 2, 2, 1, 2, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],];

		game = new RRAnimator(map);
		game.init('/rr/');
		pl = [];

		pl[3] = new Player();
		var pl_t = pl[3];
		pl_t.id = 3;
		pl_t.events = [{
			"code": "F1",
			"time": 4091,
			"attrs": {
				"start_time": 4091,
				"start_x": 5.5,
				"start_y": 8.5,
				"end_time": 4271,
				"end_x": 7.37,
				"end_y": 11.39
			}
		}];
		pl_t.bot.id = 3;
		pl_t.bot.x = 14;
		pl_t.bot.y = 14;
		pl_t.bot.d = 1;
		pl_t.bot.model = Math.round(Math.random() * 2 + 1);
		pl_t.log = {
			11: "M1",
			261: "M1",
			511: "M1",
			761: "M1",
			1011: "M1",
			1261: "M1",
			1511: "R2",
			1671: "M1",
			1921: "M1",
			2171: "M1",
			2421: "M1",
			2671: "M1",
			2921: "M1",
			3171: "M1",
			3421: "M1",
			3671: "M1",
			3921: "R2",
			4091: "F1",
			4591: "D1",
			5551: "ST"
		};

		game.addPlayer(pl_t);

		pl[1] = new Player();
		var pl_t = pl[1];
		pl_t.id = 1;
		pl_t.events = [{
			"code": "F1",
			"time": 3001,
			"attrs": {"start_time": 3001, "start_x": 6.5, "start_y": 5.5, "end_time": 3241, "end_x": 9.62, "end_y": 9.1}
		}, {
			"code": "F1",
			"time": 3501,
			"attrs": {
				"start_time": 3501,
				"start_x": 6.5,
				"start_y": 5.5,
				"end_time": 3821,
				"end_x": 7.74,
				"end_y": 11.39
			}
		}, {
			"code": "F1",
			"time": 4001,
			"attrs": {
				"start_time": 4001,
				"start_x": 6.5,
				"start_y": 5.5,
				"end_time": 4161,
				"end_x": 5.45,
				"end_y": 8.35
			}
		}, {
			"code": "F1",
			"time": 4501,
			"attrs": {
				"start_time": 4501,
				"start_x": 6.5,
				"start_y": 5.5,
				"end_time": 4721,
				"end_x": 4.96,
				"end_y": 9.68
			}
		}, {
			"code": "F1",
			"time": 5341,
			"attrs": {"start_time": 5341, "start_x": 6.5, "start_y": 6.5, "end_time": 5501, "end_x": 7.4, "end_y": 9.35}
		}];
		pl_t.bot.id = 1;
		pl_t.bot.x = 2;
		pl_t.bot.y = 2;
		pl_t.bot.d = 3;
		pl_t.bot.model = Math.round(Math.random() * 2 + 1);
		//pl_t.bot.marg = 16;
		pl_t.log = {
			11: "R2",
			171: "M1",
			511: "M1",
			841: "M1",
			1171: "M1",
			1511: "R2",
			1671: "R2",
			1841: "R2",
			2001: "M1",
			2341: "M1",
			2671: "M1",
			3001: "F1",
			3501: "F1",
			4001: "F1",
			4501: "F1",
			5001: "M1",
			5341: "F1",
			5551: "ST"
		};
		//console.log({11:"R2",171:"M1",511:"M1",841:"M1",1171:"M1",1511:"R2",1671:"R2",1841:"R2",2001:"M1",2341:"M1",2671:"M1",3001:"F1",3501:"F1",4001:"F1",4501:"F1",5001:"M1",5341:"F1",5551:"ST"});
		game.addPlayer(pl_t);

		pl[2] = new Player();
		var pl_t = pl[2];
		pl_t.id = 2;
		pl_t.events = [{
			"code": "F1",
			"time": 3371,
			"attrs": {
				"start_time": 3371,
				"start_x": 7.5,
				"start_y": 11.5,
				"end_time": 3651,
				"end_x": 7.78,
				"end_y": 5.9
			}
		}, {
			"code": "F1",
			"time": 3871,
			"attrs": {
				"start_time": 3871,
				"start_x": 7.5,
				"start_y": 11.5,
				"end_time": 4061,
				"end_x": 5.7,
				"end_y": 8.44
			}
		}, {
			"code": "F1",
			"time": 4371,
			"attrs": {
				"start_time": 4371,
				"start_x": 7.5,
				"start_y": 11.5,
				"end_time": 4551,
				"end_x": 5.63,
				"end_y": 8.61
			}
		}];
		pl_t.bot.id = 2;
		pl_t.bot.x = 2;
		pl_t.bot.y = 14;
		pl_t.bot.d = 1;
		pl_t.bot.model = Math.round(Math.random() * 2 + 1);
		//pl_t.bot.marg = 16;
		pl_t.log = {
			41: "M1",
			381: "R2",
			541: "R2",
			711: "R2",
			871: "M1",
			1211: "M1",
			1541: "M1",
			1871: "M1",
			2211: "M1",
			2541: "R2",
			2711: "M1",
			3041: "M1",
			3371: "F1",
			3871: "F1",
			4371: "F1",
			4871: "M1",
			5201: "M1",
			5541: "D1",
			5551: "ST"
		};
		game.addPlayer(pl_t);

		game.draw();
	});
}


function Pulsar(canvas, options) {
	this.options = options;
	if (!this.options['interval']) this.options['interval'] = 10;
	if (!this.options['time_limit']) this.options['time_limit'] = 30;
	this.events = [];
	this.layers = [];
	this.func = [];
	this.time_func = [];
	this.ready = 0;
	this.start_on_ready = 0;
	this.id_interval = 0;
	this.time = 0;
	this.runing = 0;
	this.shutDown = false;
	//this.updated = 1;
	if (options['multi_canvas']) {
		this.is_multi_canvas = 1;
		this.div = document.getElementById(canvas);
		for (var attr in this.options) {
			if (attr != 'interval')
				this.div[attr] = this.options[attr];
		}
	}
	else {
		this.is_multi_canvas = 0;
		var tag = document.getElementById(canvas);
		for (var attr in this.options) {
			if (attr != 'interval')
				tag[attr] = this.options[attr];
		}
		this.ctx = tag.getContext('2d');
	}
	this.setOptions = function (options) {
		this.options = options;
	}

	this.addLayer = function (name, index) {
		if (this.layers[index] != undefined) {
			console.error('Error layer creating');
			return false;
		}
		else {
			this.layers[index] = new Layer(name, index, this);
		}

		if (this.is_multi_canvas == 1) {
			var canvas_elem = document.createElement('canvas');
			canvas_elem.id = name;
			canvas_elem.className = "Pulsar_Canvas";
			canvas_elem.style.zIndex = index;
			this.div.appendChild(canvas_elem);
			for (var attr in this.options) {
				if (attr != 'interval')
					canvas_elem[attr] = this.options[attr];
			}
			this.layers[index].ctx = canvas_elem.getContext('2d');
			this.layers[index].tag = canvas_elem;
		}
		return this.layers[index];
	}
	this.getLayer = function (name) {
		for (var key in this.layers) {
			if (this.layers[key].name == name) {
				return this.layers[key];
			}
		}
		return false;
	}
	this.tryReady = function () {
		for (var key in this.layers) {
			if (this.layers[key].ready != 1) {
				this.ready = 0;
				return false;
			}
		}
		this.ready = 1;
		if (this.start_on_ready == 1) {
			this.draw();
		}
		return this;
	}

	this.draw = function () {
		if (this.ready == 1) {
			for (var key in this.layers) {
				this.layers[key].draw();
			}

			if (this.runing == 0) this.run(this);
		} else {
			this.start_on_ready = 1;
		}
	};
	this.run = function (pulsar) {
		this.runing = 1;
		this.id_interval = setInterval(function () {
			pulsar.update();
		}, this.options['interval'])
	}
	this.addFunction = function (f) {
		this.func[this.func.length + 1] = f;
		return this;
	}
	this.addTimeFunction = function (f, time) {
		this.time_func[time] = f;
		return this;
	}
	this.clearAll = function () {
		if (!this.is_multi_canvas) {
			this.ctx.clearRect(0, 0, this.options['width'], this.options['height']);
		}
	}
	this.update = function () {
		this.time += this.options['interval'];
		if (this.time > this.options['time_limit'] * 1000 || this.shutDown) clearInterval(this.id_interval);
		for (var key in this.func) {
			this.func[key](this);
		}
		for (var key in this.time_func) {
			if (key <= this.time) {
				this.time_func[key](this);
				delete this.time_func[key];
			}
		}
		this.clearAll();
		this.draw();
	}

}