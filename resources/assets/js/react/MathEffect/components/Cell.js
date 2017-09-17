import React from 'react';

const Cell = props => {


    const {x, y, size, margin} = props;
    const style = {
        width: size,
        height: size,
        margin
    };
    return (
        <div
            className={'cell ' + 'x-' + x + ' y-' + y + ' emptyCell'}
            style={ style }
            >{ x } { y }</div>
    );

};

export default Cell;