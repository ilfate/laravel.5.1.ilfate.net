import React from 'react';

const Bonus = props => {

    const { bonusConfig, size, margin } = props;
    const { x, y } = bonusConfig;

    const mainStyle = {
        width: size * 0.75,
        height: size * 0.75
    };
    const powerStyle = {
        lineHeight: (size * 0.75) + 'px'
    };

    return (

        <div
            className={ `bonus ${ bonusConfig.power > 0 ? `good` : `bad` } ${ bonusConfig.deleted ? `deleted` : `` }` }
            style={ Object.assign({}, mainStyle, {
                    marginTop: y * (size + (margin * 2)) + margin + size * 0.125,
                    marginLeft: x * (size + (margin * 2)) + margin + size * 0.125
            }) }
        >
            <div className={`power`} style={ powerStyle }>{ bonusConfig.power }</div>

        </div>



    );

};




export default Bonus;