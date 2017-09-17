import React from 'react';
import Game from './Game';

class MathEffect extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
        };
    }


    render() {
        return (
            <div id="math-effect">
                <Game />
            </div>
        );
    }
}

export default MathEffect;