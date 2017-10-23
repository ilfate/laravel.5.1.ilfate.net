import React from 'react';
import Unit from '../components/Unit';


class EndGameScreen extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            nameSaved: false
        };
        this.handleSaveName = this.handleSaveName.bind(this);
    }

    handleSaveName() {
        const checkKey = $('#checkKey').val();

        Ajax.json('/MathEffect/saveName', {
            data: 'name=' + this.input.value +
            '&checkKey=' + checkKey +
            '&_token=' + $('#laravel-token').val()
        });
        this.setState({nameSaved: true});
    }

    render() {
        const isUser = $('#userName').val() !== 'none';
        const { radius, cellSize, margin, turns, kills, points } = this.props;
        const cellRealSize = cellSize + (margin * 2);
        const style = {
            height: cellRealSize * ((radius * 2) + 1),
        };
        return (
            <div style={ style }  className="end-game-screen">
                <div className={ `block` }></div>
                <div className={ `info` }>
                    <h3>Your base belong to enemy now!</h3>
                    <p>You survived - <strong>{ turns }</strong> turns!</p>
                    <p>You killed - <strong>{ kills }</strong> units!</p>
                    <p>You earned - <strong>{ points }</strong> points!</p>
                    <p style={ {margin: '25px 0'} }><a className="label" href="/games">Try my other games</a></p>
                    { !isUser && <p className={`${ this.state.nameSaved ? `hidden-form` : `` }`}>
                        <input autoFocus type="text" name="name" ref={ node => {
                            this.input = node;
                        } } />
                        <button className="btn btn-primary" type="submit" onClick={ e => this.handleSaveName(e) }>
                            Save my name
                        </button>
                    </p> }
                    <p>
                        <a href="/MathEffect">Start again</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="/MathEffect/stats">Leaderboard</a>
                    </p>
                </div>
            </div>
        );
    }

};

export default EndGameScreen