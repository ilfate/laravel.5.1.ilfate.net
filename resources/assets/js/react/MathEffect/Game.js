import React from 'react';
import Field from './containers/Field';
import EnemiesContainer from './containers/EnemiesContainer';
import UnitsContainer from './containers/UnitsContainer';
import { moveEveryUnit, resolveUnitCollisions, updateUnitsPower, resolveCollisionsWithEnemies,
    clearDead, buffCenterUnit } from './services/unit';
import { moveEveryEnemy, updateEnemiesPower, generateNewEnemyLocation, getEnemyStartDirection,
    tryUpgradeToBoss } from './services/enemy';

export const DIRECTION_TOP = 0;
export const DIRECTION_RIGHT = 1;
export const DIRECTION_DOWN = 2;
export const DIRECTION_LEFT = 3;

const FIELD_RADIUS = 5;
const TURNS_TO_SPAWN_ENEMIES = 1;

const CELL_SIZE = 60;
const MARGIN = 2;

const situationEnemies = [
    {id: 999,
        x: 1,
        y: 0,
        was: {x: 1, y: -1},
        d: 3,
        power: 1,
        deleted: false},
    // {id: 998,
    //     x: 1,
    //     y: 1,
    //     was: {x: 1, y: 2},
    //     d: 0,
    //     power: 3,
    //     deleted: false},
];

class Game extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            enemies: situationEnemies,
            units: [],
            turnsEnemyWasCreated: 0,
            enemiesCreated: 0,
            turnNumber: 0
        };
        this.handleAddUnit = this.handleAddUnit.bind(this);
        this.addEnemy = this.addEnemy.bind(this);
        this.handleUpdateUnit = this.handleUpdateUnit.bind(this);
    }

    componentWillMount() {
        this.addEnemy();
    }

    handleAddUnit(unit) {
        let { units } = this.state;
        units.push(unit);
        this.setState({units});
    }

    addEnemy() {return;
        if (this.state.turnsEnemyWasCreated !== 0) {
            this.setState({turnsEnemyWasCreated: this.state.turnsEnemyWasCreated - 1});
            return;
        }
        const location = generateNewEnemyLocation(FIELD_RADIUS, this.state.enemies, this.state.units);
        let enemy = {
            id: this.state.enemiesCreated,
            x: location.x,
            y: location.y,
            was: {x: location.x, y: location.y},
            d: -1,
            power: 1,
            deleted: false,
            isBoss: false
        };
        enemy.d = getEnemyStartDirection(enemy, FIELD_RADIUS);
        tryUpgradeToBoss(enemy, this.state.turnNumber);
        let { enemies } = this.state;
        enemies.push(enemy);
        this.setState({ enemies, turnsEnemyWasCreated: TURNS_TO_SPAWN_ENEMIES,
            enemiesCreated: this.state.enemiesCreated + 1 });
    }

    handleUpdateUnit(newUnit) {
        let units = this.state.units.map(unit => {
            if (unit.id === newUnit.id) {
                return newUnit;
            }
            return unit;
        });
        this.setState({units});
        this.makeTurn();
    }

    makeTurn() {
        let units = buffCenterUnit(this.state.units.slice());
        units = moveEveryUnit(units, FIELD_RADIUS);
        let enemies = moveEveryEnemy(this.state.enemies.slice(), FIELD_RADIUS);
        units = resolveUnitCollisions(units);
        enemies = resolveUnitCollisions(enemies);
        units = updateUnitsPower(units);
        enemies = updateEnemiesPower(enemies);
        let { newUnits, newEnemies } = resolveCollisionsWithEnemies(units, enemies);
        newUnits = clearDead(newUnits);
        newEnemies = clearDead(newEnemies);
        this.setState({units: newUnits, enemies: newEnemies, turnNumber: this.state.turnNumber + 1});
        this.addEnemy();
    }


    render() {
        return (
            <div className="game">
                <EnemiesContainer radius={ FIELD_RADIUS } cellSize={ CELL_SIZE } margin={ MARGIN }
                                  enemies={ this.state.enemies } addEnemy={ this.handleAddEnemy }
                                  getNewEnemyLocation={ this.getNewEnemyLocation } />
                <UnitsContainer radius={ FIELD_RADIUS } cellSize={ CELL_SIZE } margin={ MARGIN }
                                units={ this.state.units } addUnit={ this.handleAddUnit }
                                updateUnit={ this.handleUpdateUnit }/>
                <Field radius={ FIELD_RADIUS } cellSize={ CELL_SIZE } margin={ MARGIN } />
            </div>
        );
    }
}

export default Game