import React from 'react';
import Enemy from '../components/Enemy';


class EnemiesContainer extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            enemiesCreated:0
        };
    }

    render() {
        const {radius, cellSize, margin } = this.props;
        const cellRealSize = cellSize + (margin * 2);
        const style = {
            width: cellRealSize * radius,
            height: cellRealSize * radius,
            marginTop: cellRealSize * (radius - 1),
            marginLeft: cellRealSize * (radius - 1),
        };
        const enemiesList = this.props.enemies.map(enemy => !enemy.deleted && <Enemy key={ enemy.id }
                                                                             enemyConfig={ enemy }
                                                                             size={ cellSize } margin={ margin } />)
        return (
            <div style={ style } className="enemies">
                { enemiesList }
            </div>
        );
    }

};

export default EnemiesContainer