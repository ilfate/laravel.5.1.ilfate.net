import React from 'react';
import { PlayArea } from './components/PlayArea';
import { Target, TARGET_TYPE_CIRCLE } from './components/Target/index';


const waveConfig = {
    'waves': [
        {targets: 2, time: 4000, type: TARGET_TYPE_CIRCLE, size: 35},
        {targets: 3, time: 3000, type: TARGET_TYPE_CIRCLE, size: 30},
        {targets: 3, time: 2000, type: TARGET_TYPE_CIRCLE, size: 25},
        {targets: 30, timeX: 1000, timeY:1500, type: TARGET_TYPE_CIRCLE, size: 25},
    ],
};

class Clicker extends React.Component {

    constructor(props) {
        super(props);
        this.addTarget = this.addTarget.bind(this);
        this.start = this.start.bind(this);
        this.stop = this.stop.bind(this);
        this.toggle = this.toggle.bind(this);
        this.handleDestroy = this.handleDestroy.bind(this);
        this.state = {
            running: 0,
            targets: [],
            counter: 0,
            killedTargets: 0,
            currentWave: 0,
            targetNumberInWave: 0,
        };
        this.buttonText = ['Start', 'Stop'];
    }
    addTarget() {
         let targets = this.state.targets;
         let id = this.state.counter++;
         const currentWaveConfig = waveConfig.waves[this.state.currentWave];
         targets[id] = <Target key={id} id={id} onDestroy={this.handleDestroy} config={currentWaveConfig}/>;
         let targetNumberInWave = this.state.targetNumberInWave + 1;
         let newState = {targets, targetNumberInWave};
         if (targetNumberInWave >= currentWaveConfig.targets) {
             newState.targetNumberInWave = 0;
             newState.currentWave = this.state.currentWave + 1;
             if (typeof waveConfig.waves[this.state.currentWave + 1] === 'undefined') {
                 // game finished
                 this.stop();
             }
         }
         this.setState(newState);
    }
    handleDestroy(id) {
        let targets = this.state.targets;
        delete targets[id];
        this.setState({targets, killedTargets: this.state.killedTargets + 1});
    }
    componentDidMount() {
          // this.interval = setInterval(this.addTarget, 1000);
    }

    start() {
        this.interval = setInterval(this.addTarget, 1000);
        this.setState({running : 1});
    }
    stop() {
        clearInterval(this.interval);
        this.setState({running : 0});
    }
    toggle() {
        if(this.state.running === 0) {
            this.start();
        } else {
            this.stop();
        }
    }

    render() {
        return (
            <div>
                <a className="btn" onClick={this.toggle}>{this.buttonText[this.state.running]}</a>
                <PlayArea size="400">
                    {this.state.targets}
                </PlayArea>
            </div>
        );
    }
}

export default Clicker;