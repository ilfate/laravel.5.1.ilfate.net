import React from 'react';
import {Motion, spring} from 'react-motion';

const style = {
    backgroundColor: '#aa4444',
    position: 'absolute'
};

export const Circle = (props) => {

    style.width = props.size;
    style.height = props.size;
    style.borderRadius = props.size;
    // style.top = props.y - (props.size / 2);
    // style.left = props.x - (props.size / 2);
    // style.transitionTimingFunction = 'ease-out';
    // style.transition = 'top ' + props.timeY + 'ms 20ms, left ' + props.timeX + 'ms 20ms';
    let x = props.x - (props.size / 2);
    let y = props.y - (props.size / 2);
    let springConfig = {stiffness: 52, damping: 17};
    return (
    <Motion defaultStyle={{x, y}} style={{x: spring(0), y: spring(0)}}>
        {value => <div style={ Object.assign({ ...style }, { top:value.y, left:value.x })} onClick={props.onClick} >
        </div>}
    </Motion>

    );

};

Circle.defaultProps = {
    size: 30,
    timeX: 2000,
    timeY: 1000,
};