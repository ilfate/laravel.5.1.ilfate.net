import React from 'react';
import { Circle } from './Circle';
import { coordinatsFromAngle } from '../../../Math';

export const TARGET_TYPE_CIRCLE = 'circle';

export class Target extends React.Component {

    constructor(props) {
        super(props);
        this.start = this.start.bind(this);
        this.handleClick = this.handleClick.bind(this);
        let coordinates = coordinatsFromAngle(200, Math.random() * 360);
        let size = 30;
        if (typeof props.config.size !== 'undefined') size = props.config.size;
        this.state = {
            size: size,
            x: coordinates.x,
            y: coordinates.y
        };
        this.config = {
            type: typeof props.config.type !== 'undefined' ? props.config.type : 'circle',
            time: typeof props.config.time !== 'undefined' ? props.config.time : false,
            timeX: typeof props.config.timeX !== 'undefined' ? props.config.timeX : false,
            timeY: typeof props.config.timeY !== 'undefined' ? props.config.timeY : false,
        };
        if (!this.config.timeX && this.config.time) this.config.timeX = this.config.time;
        if (!this.config.timeY && this.config.time) this.config.timeY = this.config.time;
    }
    start() {
        //this.setState({x:0, y: 0});
    }
    handleClick() {
        this.props.onDestroy(this.props.id);
    }
    componentDidMount() {
        //this.timeout = setTimeout(this.start, 30);
    }

    render() {
        switch (this.config.type) {
            case TARGET_TYPE_CIRCLE:
                return <Circle
                    size={this.state.size}
                    x={this.state.x}
                    y={this.state.y}
                    timeX={this.config.timeX}
                    timeY={this.config.timeY}
                    onClick={this.handleClick}/>
        }
    }
}


// export default Target;