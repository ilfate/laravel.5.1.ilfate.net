import React from 'react';
import Hammer from 'react-hammerjs';
import { DIRECTION_TOP, DIRECTION_RIGHT, DIRECTION_DOWN, DIRECTION_LEFT } from '../Game';
import { colors } from '../services/unit';
import { Motion, spring } from 'react-motion';
import { BlockWithArrow } from './BlockWithArrow';


class Unit extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            pan: {d: false, r: 0, pointerId: false}
        };
        this.handlePan = this.handlePan.bind(this);
        this.handlePanEnd = this.handlePanEnd.bind(this);
        this.checkPanMove = this.checkPanMove.bind(this);
    }

    handlePan(e) {
        const { pointerId } = e.changedPointers[0];
        switch(e.additionalEvent) {
            case 'panup': !this.checkPanMove(DIRECTION_TOP, e) && this.setState({ pan: { d: 0, r: e.distance, pointerId: this.state.pan.pointerId } }); break;
            case 'panright': !this.checkPanMove(DIRECTION_RIGHT, e) && this.setState({ pan: { d: 1, r: e.distance, pointerId: this.state.pan.pointerId } }); break;
            case 'pandown': !this.checkPanMove(DIRECTION_DOWN, e) && this.setState({ pan: { d: 2, r: e.distance, pointerId: this.state.pan.pointerId } }); break;
            case 'panleft': !this.checkPanMove(DIRECTION_LEFT, e) && this.setState({ pan: { d: 3, r: e.distance, pointerId: this.state.pan.pointerId } }); break;
            default: this.setState({ pan: { d: false, r: 0, pointerId } }); return;
        }

    }
    handlePanEnd(e) {
        this.setState({ pan: { d: false, r: 0 } });
    }
    checkPanMove(d, e) {
        const { size, onSetDirection, unitConfig } = this.props;
        const { pointerId } = e.changedPointers[0];
        if (e.distance > size * 1.5 || pointerId === this.state.pan.pointerId) {
            this.setState({ pan: { d: false, r: 0, pointerId } });
            return true;
        }
        if (e.distance > size) {
            this.setState({ pan: { d: false, r: 0, pointerId } });
            onSetDirection(unitConfig, d);
            return true;
        }
        return false;
    }

    render() {

        const {unitConfig, size, margin, onSetDirection} = this.props;
        const {x, y} = unitConfig;

        const dividedPower = Math.ceil(unitConfig.power / 4);
        const color = colors[dividedPower > 10 ? 10 : dividedPower];
        const mainStyle = {
            width: size,
            height: size,
        };
        const iconStyle = {
            fontSize: size * 0.75
        };
        let panMargin = { x: 0, y: 0 };
        if (this.state.pan.d !== false) {
            switch (this.state.pan.d) {
                case 0: panMargin.y = - this.state.pan.r; break;
                case 1: panMargin.x = this.state.pan.r; break;
                case 2: panMargin.y = this.state.pan.r; break;
                case 3: panMargin.x = - this.state.pan.r; break;
            }
        }
        const horizontalIconStyle = Object.assign({lineHeight: (size * 2) + 'px'}, iconStyle);
        let opacity = unitConfig.deleted ? 0 : 1;
        return (
            <Motion defaultStyle={{x: unitConfig.was.x, y: unitConfig.was.y, opacity: 1}}
                    style={{x: spring(x), y: spring(y), opacity: spring(opacity)}}>
                {value =>
                    <Hammer direction={ 'DIRECTION_ALL' }
                            onPan={ (e) => {
                                this.handlePan(e);
                            } }
                            onPanEnd={ e => this.handlePanEnd(e) }
                        //onTap={ (e) => {console.log({type:e.type, e}) } }
                        //onSwipe={ (e) => {console.log({type:e.type, e}) } }
                    >
                        <div
                            className={ `unit ${ unitConfig.deleted ? `deleted`: `` }` }
                            style={ Object.assign({}, mainStyle, {
                                transform: `translate(${ value.x * (size + (margin * 2)) + margin + panMargin.x }px, ${ value.y * (size + (margin * 2)) + margin + panMargin.y }px)`,
                                //marginTop: value.y * (size + (margin * 2)) + margin + panMargin.y,
                                //marginLeft: value.x * (size + (margin * 2)) + margin + panMargin.x,
                                opacity: value.opacity
                            }) }
                        >
                            <BlockWithArrow d={ unitConfig.d } size={ size } color={ color }/>
                            <div className="buttons">
                                {unitConfig.d !== 0 && <div onClick={ () => {
                                    onSetDirection(unitConfig, DIRECTION_TOP)
                                } }
                                                            className="top">
                                    <i style={ iconStyle } className="fa fa-arrow-circle-up"/>
                                </div>}
                                {unitConfig.d !== 2 && <div onClick={ () => {
                                    onSetDirection(unitConfig, DIRECTION_DOWN)
                                } }
                                                            className="bottom">
                                    <i style={ iconStyle } className="fa fa-arrow-circle-down"/>
                                </div>}

                                {unitConfig.d !== 1 && <div onClick={ () => {
                                    onSetDirection(unitConfig, DIRECTION_RIGHT)
                                } }
                                                            className="right">
                                    <i style={ horizontalIconStyle } className="fa fa-arrow-circle-right"/>
                                </div>}

                                {unitConfig.d !== 3 && <div onClick={ () => {
                                    onSetDirection(unitConfig, DIRECTION_LEFT)
                                } }
                                                            className="left">
                                    <i style={ horizontalIconStyle } className="fa fa-arrow-circle-left"/>
                                </div>
                                }

                            </div>
                            <div className="power" style={{lineHeight: size + 'px'}}>{ unitConfig.power }</div>

                        </div>
                    </Hammer>
                }
            </Motion>
        );
    }

};




export default Unit;