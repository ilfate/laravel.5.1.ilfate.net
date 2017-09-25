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
        const {radius, cellSize, margin } = this.props;
        const cellRealSize = cellSize + (margin * 2);
        const style = {
            marginTop: cellRealSize * (radius - 1),
            marginLeft: cellRealSize * (radius - 1),
        };
        const enemiesList = this.props.enemies.map(enemy => !enemy.deleted && <Enemy key={ enemy.id }
                                                                             enemyConfig={ enemy }
                                                                             onMouseEnter={ this.handleMouseEnter }
                                                                             onMouseLeave={ this.handleMouseLeave }
                                                                             size={ cellSize } margin={ margin } />)
        return (
            <div style={ style } className="enemies">
                { this.state.pathVisible && <Path enemy={ this.state.pathFor }
                                                  radius={ radius }
                                                  size={ cellSize }
                                                  margin={ margin } /> }
                <div>{ enemiesList }</div>
            </div>
        );
    }

};

export default EnemiesContainer