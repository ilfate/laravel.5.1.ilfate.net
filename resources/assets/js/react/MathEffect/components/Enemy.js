import React from 'react';

const Enemy = props => {


    const { enemyConfig, size, margin } = props;
    const { x, y } = enemyConfig;

    const style = {
        width: size,
        height: size,
        marginTop: y * (size + (margin * 2)),
        marginLeft: x * (size + (margin * 2)),
        backgroundColor: '#AA3333'
    };
    return (

        <div
            className={ 'enemy' }
            style={ style }
            >
            <span className="power">{ enemyConfig.power }</span>

        </div>

    );

};




export default Enemy;