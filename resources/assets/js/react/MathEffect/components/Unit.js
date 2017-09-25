import React from 'react';
import Hammer from 'react-hammerjs';
import { DIRECTION_TOP, DIRECTION_RIGHT, DIRECTION_DOWN, DIRECTION_LEFT } from '../Game';
import { colors } from '../services/unit';
import { Motion, spring } from 'react-motion';
import { BlockWithArrow } from './BlockWithArrow';


const Unit = props => {


    const { unitConfig, size, margin, onSetDirection } = props;
    const { x, y } = unitConfig;

    const dividedPower = Math.ceil(unitConfig.power / 4);
    const color = colors[dividedPower > 10 ? 10 : dividedPower];
    const mainStyle = {
        width: size,
        height: size,
    };
    const iconDivStyle = {
        width: size / 2
    }
    const iconStyle = {
        fontSize: size
    }
    const horizontalIconStyle = Object.assign({lineHeight: (size * 2) + 'px'}, iconStyle);
    return (
        <Motion defaultStyle={{x:unitConfig.was.x, y:unitConfig.was.y}} style={{x: spring(x), y: spring(y)}}>
            {value =>
                <Hammer direction={Hammer.ALL}
                        onPan={ (e) => {console.log({type:e.type, e}) } }
                        onTap={ (e) => {console.log({type:e.type, e}) } }
                        onSwipe={ (e) => {console.log({type:e.type, e}) } }>
                    <div
                        className={ 'unit' }
                        style={ Object.assign({}, mainStyle, {marginTop:value.y * (size + (margin * 2)), marginLeft: value.x * (size + (margin * 2))}) }
                        >
                        <BlockWithArrow d={ unitConfig.d } size={ size } color={ color } />
                        <div className="buttons">
                            {unitConfig.d !== 0 && <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_TOP) } }
                                                        className="top">
                                <i style={ iconStyle } className="fa fa-arrow-circle-up" />
                            </div>}
                            {unitConfig.d !== 2 && <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_DOWN) } }
                                                        className="bottom">
                                <i style={ iconStyle } className="fa fa-arrow-circle-down"/>
                            </div>}
                            <div className="horizontal">
                                {unitConfig.d !== 1 && <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_RIGHT) } }
                                                            style={ iconDivStyle }
                                                            className="right">
                                    <i style={ horizontalIconStyle } className="fa fa-arrow-circle-right"/>
                                </div>}

                                {unitConfig.d !== 3 && <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_LEFT) } }
                                                            className="left">
                                    <i style={ horizontalIconStyle } className="fa fa-arrow-circle-left"/>
                                </div>
                                }
                            </div>
                        </div>
                        <div className="power" style={{lineHeight: size + 'px'}}>{ unitConfig.power }</div>

                    </div>
                </Hammer>
            }
        </Motion>
    );

};




export default Unit;