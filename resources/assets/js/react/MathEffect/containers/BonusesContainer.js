import React from 'react';
import Bonus from '../components/Bonus';

class BonusesContainer extends React.Component {

    render() {
        const { radius, cellSize, margin, bonuses } = this.props;
        const cellRealSize = cellSize + (margin * 2);
        const style = {
            marginTop: cellRealSize * radius,
            marginLeft: cellRealSize * radius,
        };
        const bonusesList = bonuses.map(bonus => <Bonus key={ bonus.id }
                                                        bonusConfig={ bonus }
                                                        size={ cellSize } margin={ margin } />)
        return (
            <div style={ style } className="bonuses">
                { bonusesList }
            </div>
        );
    }

};

export default BonusesContainer;