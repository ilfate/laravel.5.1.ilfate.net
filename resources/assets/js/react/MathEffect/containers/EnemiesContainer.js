import React from 'react';
import Enemy from '../components/Enemy';
import Path from '../components/Path';

class EnemiesContainer extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            pathVisible: false,
            pathFor: {}
        };
        this.handleMouseEnter = this.handleMouseEnter.bind(this);
        this.handleMouseLeave = this.handleMouseLeave.bind(this);
    }

    handleMouseEnter(enemy) {
        this.setState({pathVisible: true, pathFor: enemy});
    }
    handleMouseLeave() {
        this.setState({pathVisible: false, pathFor: {}});
    }

    render() {
        const { radius, cellSize, margin, gameRunning } = this.props;
        const cellRealSize = cellSize + (margin * 2);
        const style = {
            marginTop: cellRealSize * radius,
            marginLeft: cellRealSize * radius,
        };
        const enemiesList = this.props.enemies.map(enemy => <Enemy key={ enemy.id }
                                                                             enemyConfig={ enemy }
                                                                             onMouseEnter={ this.handleMouseEnter }
                                                                             onMouseLeave={ this.handleMouseLeave }
                                                                             size={ cellSize } margin={ margin }
                                                                             gameRunning={ gameRunning } />)
        return (
            <div style={ style } className="enemies">
                { this.state.pathVisible && gameRunning && <Path enemy={ this.state.pathFor }
                                                  radius={ radius }
                                                  size={ cellSize }
                                                  margin={ margin } /> }
                <div>{ enemiesList }</div>
            </div>
        );
    }

};

export default EnemiesContainer