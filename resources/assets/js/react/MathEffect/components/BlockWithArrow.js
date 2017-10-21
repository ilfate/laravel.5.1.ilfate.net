import React from 'react';


export const BlockWithArrow = props => {

    let arrowMarginLeft = 0.1325 * props.size;
    let arrowMarginTop = 0.1325 * props.size;
    const arrowAddConst = 0.024 * props.size;
    switch (props.d) {
        case 0: arrowMarginTop += arrowAddConst; break;
        case 1: arrowMarginLeft -= arrowAddConst; break;
        case 2: arrowMarginTop -= arrowAddConst; break;
        case 3: arrowMarginLeft += arrowAddConst; break;
    }

    const blockStyle = {
        width: props.d === 1 || props.d === 3 ? props.size / 2 : props.size,
        height: props.d === 0 || props.d === 2 ? props.size / 2 : props.size,
        backgroundColor: props.color,
        marginTop: props.d === 0 ? props.size / 2 : 0,
        marginLeft: props.d === 3 ? props.size / 2 : 0,
    };
    const arrowStyle = {
        backgroundColor: props.color,
        marginTop: arrowMarginTop,
        marginLeft: arrowMarginLeft,
        width: 0.73 * props.size,
        height: 0.73 * props.size,
    };

    return (
        <div className="block-with-arrow">
            <div className="block" style={ blockStyle }></div>
            { props.d !== -1 && <div className="arrow" style={ arrowStyle }></div> }
        </div>
    );
};