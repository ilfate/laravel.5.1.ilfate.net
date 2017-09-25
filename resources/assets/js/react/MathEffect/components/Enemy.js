import React from 'react';
import {Motion, spring} from 'react-motion';
import { BlockWithArrow } from './BlockWithArrow';

const Enemy = props => {


    const { enemyConfig, size, margin } = props;
    const { x, y } = enemyConfig;
    const color = enemyConfig.isBoss ? '#C21348' : '#F07818';
    const mainStyle = {
        width: size,
        height: size
    };
    const powerStyle = {
        lineHeight: size + 'px'

    };
    return (
        <Motion defaultStyle={{x: enemyConfig.was.x, y: enemyConfig.was.y}} style={{x: spring(x), y: spring(y)}}>
            {value =>
                <div
                    onMouseEnter={ () => props.onMouseEnter(enemyConfig) }
                    onMouseLeave={ props.onMouseLeave }
                    className={ 'enemy' }
                    style={ Object.assign({}, mainStyle,
                        {marginTop: value.y * (size + (margin * 2)), marginLeft: value.x * (size + (margin * 2))}
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