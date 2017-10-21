import React from 'react';
import Unit from '../components/Unit';


class EndGameScreen extends React.Component {

    constructor(props) {
        super(props);
    }



    render() {
        const { radius, cellSize, margin } = this.props;
        const cellRealSize = cellSize + (margin * 2);
        const style = {
            height: cellRealSize * ((radius * 2) + 1),
        };
        return (
            <div style={ style }  className="end-game-screen">
                <div className={ `block` }></div>
            </div>
        );
    }

};

export default EndGameScreen