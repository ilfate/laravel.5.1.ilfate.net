import React from 'react';
import Unit from '../components/Unit';


class UnitsContainer extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            unitsCreated:0
        };
        this.addUnit = this.addUnit.bind(this);
        this.handleSetDirection = this.handleSetDirection.bind(this);
    }

    componentWillMount() {
        this.addUnit(1);
    }

    addUnit(power) {
        let newUnit = {
            id: this.state.unitsCreated,
            x: 0,
            y: 0,
            was: {x: 0, y: 0},
            d: -1,
            power: power ? power : 0,
            turnsInactive: 0,
            decayTurnLimit : 4,
            deleted: false
        };
        this.props.addUnit(newUnit);
        this.setState({unitsCreated: this.state.unitsCreated + 1});
    }

    handleSetDirection(unit, direction) {
        if (unit.d === direction) return false;
        unit.d = direction;
        if ((unit.x === 0 && unit.y === 0) || this.props.units.filter(
                unit => {return unit.x === 0 && unit.y === 0 && unit.d === -1}
            ).length === 0) {
            this.addUnit();
        }
        this.props.updateUnit(unit);
    }

    render() {
        const {radius, cellSize, margin, gameRunning} = this.props;
        const cellRealSize = cellSize + (margin * 2);
        const style = {
            marginTop: cellRealSize * radius,
            marginLeft: cellRealSize * radius,
        };
        const unitList = this.props.units.map(unit => <Unit key={ unit.id }
                                                            unitConfig={ unit }
                                                            size={ cellSize } margin={ margin }
                                                            onSetDirection={ this.handleSetDirection }
                                                            gameRunning={ gameRunning } />);
        return (
            <div style={ style } className="units">
                { unitList }
            </div>
        );
    }

};

export default UnitsContainer