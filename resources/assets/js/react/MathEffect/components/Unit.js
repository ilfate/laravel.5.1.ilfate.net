import React from 'react';
import Hammer from 'react-hammerjs';
import { DIRECTION_TOP, DIRECTION_RIGHT, DIRECTION_DOWN, DIRECTION_LEFT } from '../Game';

const Unit = props => {


    const { unitConfig, size, margin, onSetDirection } = props;
    const { x, y } = unitConfig;

    const style = {
        width: size,
        height: size,
        marginTop: y * (size + (margin * 2)),
        marginLeft: x * (size + (margin * 2)),
        backgroundColor: '#33AA33'
    };
    return (
        <Hammer direction={Hammer.ALL}
                onPan={ (e) => {console.log({type:e.type, e}) } }
                onTap={ (e) => {console.log({type:e.type, e}) } }
                onSwipe={ (e) => {console.log({type:e.type, e}) } }>
            <div
                className={ 'unit' }
                style={ style }
                >
                <div className="buttons">
                    <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_TOP) } } className="top">
                        <i className="fa fa-arrow-circle-up"></i>
                    </div>
                    <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_RIGHT) } } className="right">
                        <i className="fa fa-arrow-circle-right"></i>
                    </div>
                    <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_DOWN) } } className="bottom">
                        <i className="fa fa-arrow-circle-down"></i>
                    </div>
                    <div onClick={ () => { onSetDirection(unitConfig, DIRECTION_LEFT) } } className="left">
                        <i className="fa fa-arrow-circle-left"></i>
                    </div>
                </div>
                <span className="power">{ unitConfig.power }</span>

            </div>
        </Hammer>
    );

};




export default Unit;