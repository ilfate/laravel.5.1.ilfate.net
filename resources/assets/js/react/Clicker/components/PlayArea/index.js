import React from 'react';


const styleContainer = {
    margin: '50px auto 0 auto',
    backgroundColor: '#fbfbfb',
    position: 'relative',
    userSelect: 'none'
};
const styleArea = {
    position: 'absolute'
};



export const PlayArea = (props) => {
    const halfSize = parseInt(props.size / 2);
    styleContainer.width = parseInt(props.size);
    styleContainer.height = parseInt(props.size);
    styleArea.width = halfSize;
    styleArea.height = halfSize;
    styleArea.margin = halfSize + 'px 0 0 ' + halfSize + 'px';
    return (
        <div className="play-area" style={ {...styleContainer} }>
            <div style={ {...styleArea} }>
                {props.children}
            </div>
        </div>
    );
};
