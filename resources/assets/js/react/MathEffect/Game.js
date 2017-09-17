import React from 'react';
import Field from './containers/Field';
import EnemiesContainer from './containers/EnemiesContainer';
import UnitsContainer from './containers/UnitsContainer';
import { moveEveryUnit, resolveUnitCollisions, updateUnitsPower, resolveCollisionsWithEnemies,
    clearDead } from './services/unit';
import { moveEveryEnemy, updateEnemiesPower, generateNewEnemyLocation, getEnemyStartDirection } from './services/enemy';

export const DIRECTION_TOP = 0;
export const DIRECTION_RIGHT = 1;
export const DIRECTION_DOWN = 2;
export const DIRECTION_LEFT = 3;

const FIELD_RADIUS = 5;
const TURNS_TO_SPAWN_ENEMIES = 1;

class Game extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            enemies: [],
            units: [],
            turnsEnemyWasCreated: 0,
            enemiesCreated: 0
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

    addEnemy() {
        if (this.state.turnsEnemyWasCreated !== 0) {
            this.setState({turnsEnemyWasCreated: this.state.turnsEnemyWasCreated - 1});
            return;
        }
        const location = generateNewEnemyLocation(FIELD_RADIUS, this.state.enemies, this.state.units);
        let enemy = {
            id: this.state.enemiesCreated,
            x: location.x,
            y: location.y,
            d: -1,
            power: 1,
            deleted: false
        };
        enemy.d = getEnemyStartDirection(enemy, FIELD_RADIUS);
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
        let units = moveEveryUnit(this.state.units.slice(), FIELD_RADIUS);
        let enemies = moveEveryEnemy(this.state.enemies.slice(), FIELD_RADIUS);
        units = resolveUnitCollisions(units);
        enemies = resolveUnitCollisions(enemies);
        let { newUnits, newEnemies } = resolveCollisionsWithEnemies(units, enemies);
        newUnits = clearDead(newUnits);
        newEnemies = clearDead(newEnemies);
        newUnits = updateUnitsPower(newUnits);
        newEnemies = updateEnemiesPower(newEnemies);
        this.setState({units: newUnits, enemies: newEnemies});
        this.addEnemy();
    }


    render() {
        const cellSize = 60;
        const margin = 2;
        return (
            <div>
                <EnemiesContainer radius={ FIELD_RADIUS } cellSize={ cellSize } margin={ margin }
                                  enemies={ this.state.enemies } addEnemy={ this.handleAddEnemy }
                                  getNewEnemyLocation={ this.getNewEnemyLocation } />
                <UnitsContainer radius={ FIELD_RADIUS } cellSize={ cellSize } margin={ margin }
                                units={ this.state.units } addUnit={ this.handleAddUnit }
                                updateUnit={ this.handleUpdateUnit }/>
                <Field radius={ FIELD_RADIUS } cellSize={ cellSize } margin={ margin } />
            </div>
        );
    }
}

export default Game