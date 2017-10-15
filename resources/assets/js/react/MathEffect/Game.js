import React from 'react';
import Field from './containers/Field';
import EnemiesContainer from './containers/EnemiesContainer';
import UnitsContainer from './containers/UnitsContainer';
import { moveEveryUnit, resolveUnitCollisions, updateUnitsPower, resolveCollisionsWithEnemies,
    clearDead, buffCenterUnit } from './services/unit';
import { moveEveryEnemy, updateEnemiesPower, generateNewEnemyLocation, getEnemyStartDirection,
    tryUpgradeToBoss, checkLooseConditions } from './services/enemy';
import { allCombatSituationUnits, allCombatSituationEnemies } from './test/situations';

export const DIRECTION_TOP = 0;
export const DIRECTION_RIGHT = 1;
export const DIRECTION_DOWN = 2;
export const DIRECTION_LEFT = 3;

const FIELD_RADIUS = 4;
const TURNS_TO_SPAWN_ENEMIES = 1;

const CELL_SIZE = 60;
const MARGIN = 2;
const TEST = false;

const situationEnemies = [
];

const situationUnits = [
];
const situationBonuses = [];

class Game extends React.Component {

    constructor(props) {
        super(props);

        let cellSize = CELL_SIZE;
        if (window.innerWidth < (CELL_SIZE + MARGIN) * (FIELD_RADIUS * 2 + 1)) {
            cellSize = 50;
        }
        console.log(window.innerWidth , (CELL_SIZE + MARGIN) * (FIELD_RADIUS * 2 + 1));

        this.state = {
            enemies: TEST ? allCombatSituationEnemies : situationEnemies,
            units: TEST ? allCombatSituationUnits : situationUnits,
            bonuses: situationBonuses,
            turnsEnemyWasCreated: 0,
            enemiesCreated: 0,
            turnNumber: 0,
            gameRunning: true,
            collisionLocations: [],
            cellSize: cellSize,
            margin: MARGIN,
        };
        this.handleAddUnit = this.handleAddUnit.bind(this);
        this.addEnemy = this.addEnemy.bind(this);
        this.handleUpdateUnit = this.handleUpdateUnit.bind(this);
    }

    componentWillMount() {
        if (!TEST) this.addEnemy();
    }

    handleAddUnit(unit) {
        if (!this.state.gameRunning) return;
        let { units } = this.state;
        units.push(unit);
        this.setState({units});
    }

    addEnemy() {
        if (!this.state.gameRunning) return;
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
        if (!this.state.gameRunning) return;
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
        let units = clearDead(this.state.units.slice());
        units = buffCenterUnit(units);
        units = moveEveryUnit(units, FIELD_RADIUS);
        let enemies = clearDead(this.state.enemies.slice());
        enemies = moveEveryEnemy(enemies);
        units = updateUnitsPower(units);
        enemies = updateEnemiesPower(enemies);
        let { newUnits, newEnemies, collisionLocations } = resolveCollisionsWithEnemies(units, enemies);
        console.log(collisionLocations);
        newUnits = resolveUnitCollisions(newUnits);
        newEnemies = resolveUnitCollisions(newEnemies);
        if (checkLooseConditions(newEnemies)) {
            this.looseGame();
        }
        this.setState({ units: newUnits, enemies: newEnemies, turnNumber: this.state.turnNumber + 1, collisionLocations });
        if (!TEST) this.addEnemy();
    }

    looseGame() {
        this.setState({gameRunning: false})
    }


    render() {
        return (
            <div className="game">
                <EnemiesContainer radius={ FIELD_RADIUS } cellSize={ this.state.cellSize } margin={ MARGIN }
                                  enemies={ this.state.enemies } addEnemy={ this.handleAddEnemy }
                                  getNewEnemyLocation={ this.getNewEnemyLocation } />
                <UnitsContainer radius={ FIELD_RADIUS } cellSize={ this.state.cellSize } margin={ MARGIN }
                                units={ this.state.units } addUnit={ this.handleAddUnit }
                                updateUnit={ this.handleUpdateUnit }/>
                <Field radius={ FIELD_RADIUS }
                       cellSize={ this.state.cellSize }
                       margin={ MARGIN }
                       collisionLocations={ this.state.collisionLocations } />
            </div>
        );
    }
}

export default Game