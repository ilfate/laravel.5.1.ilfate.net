import React from 'react';
import {Motion, spring} from 'react-motion';
import { BlockWithArrow } from './BlockWithArrow';

const Enemy = props => {


    const { enemyConfig, size, margin, gameRunning } = props;
    const { x, y } = enemyConfig;
    const color = enemyConfig.isBoss ? '#C21348' : '#F07818';
    const mainStyle = {
        width: size,
        height: size
    };
    const powerStyle = {
        lineHeight: size + 'px'
    };
    let opacity = enemyConfig.deleted ? 0 : 1;
    return (
        <Motion defaultStyle={{ x: enemyConfig.was.x, y: enemyConfig.was.y, v: 1 }} style={{ x: spring(x), y: spring(y), v: spring(opacity) }}>
            {value =>
                <div
                    onMouseEnter={ () => props.onMouseEnter(enemyConfig) }
                    onMouseLeave={ props.onMouseLeave }
                    className={ `enemy ${ !gameRunning ? `game-end` : `` }` }
                    style={ Object.assign({}, mainStyle, {
                            transform: `translate(${ value.x * (size + (margin * 2)) + margin }px, ${ value.y * (size + (margin * 2)) + margin }px)`,
                            //marginTop: value.y * (size + (margin * 2)) + margin,
                            //marginLeft: value.x * (size + (margin * 2)) + margin
                    },
                        {opacity: value.v}
                    ) }
                >
                    <BlockWithArrow d={ enemyConfig.d } size={ size } color={ color } />
                    <div className="power" style={ powerStyle }>{ enemyConfig.power }</div>

                </div>
            }
        </Motion>

    );

};




export default Enemy;