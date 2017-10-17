import React from 'react';
import Field from './containers/Field';
import EnemiesContainer from './containers/EnemiesContainer';
import UnitsContainer from './containers/UnitsContainer';
import BonusesContainer from './containers/BonusesContainer';
import { moveEveryUnit, resolveUnitCollisions, updateUnitsPower, resolveCollisionsWithEnemies,
    clearDead, buffCenterUnit } from './services/unit';
import { moveEveryEnemy, updateEnemiesPower, generateNewEnemyLocation, getEnemyStartDirection,
    tryUpgradeToBoss, checkLooseConditions } from './services/enemy';
import { spawnBonus, resolveCollisionsWithBonuses } from './services/bonuses';
import { allCombatSituationUnits, allCombatSituationEnemies, allCombatSituationBonuses } from './test/situations';

export const DIRECTION_TOP = 0;
export const DIRECTION_RIGHT = 1;
export const DIRECTION_DOWN = 2;
export const DIRECTION_LEFT = 3;

const FIELD_RADIUS = 4;
const TURNS_TO_SPAWN_ENEMIES = 1;

const CHANCE_TO_SPAWN_BONUS = 15;
const POINTS_PER_KILL = 5;

const CELL_SIZE = 60;
const MARGIN = 2;
const TEST = true;

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
            bonuses: TEST ? allCombatSituationBonuses : situationBonuses,
            turnsEnemyWasCreated: 0,
            enemiesCreated: 0,
            bonusesCreated: 0,
            turnNumber: 0,
            enemiesKilled: 0,
            pointsEarned: 0,
            gameRunning: true,
            collisionLocations: [],
            cellSize: cellSize,
            margin: MARGIN,
        };
        this.handleAddUnit = this.handleAddUnit.bind(this);
        this.addEnemy = this.addEnemy.bind(this);
        this.handleUpdateUnit = this.handleUpdateUnit.bind(this);
        this.tryToCreateBonus = this.tryToCreateBonus.bind(this);
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

    tryToCreateBonus(bonuses) {
        if (rand (1, 100) <= CHANCE_TO_SPAWN_BONUS) {
            let newBonus = spawnBonus(bonuses, this.state.units, this.state.enemies, FIELD_RADIUS);
            newBonus.id = this.state.bonusesCreated;
            this.setState({ bonuses: [newBonus, ...bonuses], bonusesCreated: this.state.bonusesCreated + 1 });
        }
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
        let bonuses = clearDead([...this.state.bonuses]);
        let units = clearDead([...this.state.units]);
        units = buffCenterUnit(units);
        units = moveEveryUnit(units, FIELD_RADIUS);
        let enemies = clearDead([...this.state.enemies]);
        enemies = moveEveryEnemy(enemies);
        units = updateUnitsPower(units);
        enemies = updateEnemiesPower(enemies);
        let { newUnits, newEnemies, collisionLocations, enemiesKilled } = resolveCollisionsWithEnemies(units, enemies);

        newUnits = resolveUnitCollisions(newUnits);

        // how two units can end up in the same place?
        // If one is standing and other one is moving to it?

        newEnemies = resolveUnitCollisions(newEnemies);
        [ bonuses, newUnits, newEnemies, collisionLocations ] = resolveCollisionsWithBonuses(bonuses, newUnits, newEnemies, collisionLocations);
        if (checkLooseConditions(newEnemies)) {
            this.looseGame();
        }
        if (!TEST) this.tryToCreateBonus(bonuses);
        this.setState({
            units: newUnits,
            enemies: newEnemies,
            turnNumber: this.state.turnNumber + 1,
            enemiesKilled: this.state.enemiesKilled + enemiesKilled,
            collisionLocations });
        if (!TEST) this.addEnemy();
    }

    looseGame() {
        this.setState({gameRunning: false});

        var checkKey = $('#checkKey').val();

        Ajax.json('/MathEffect/save', {
            //params : '__csrf=' + Ajax.getCSRF(),
            data: 'turnsSurvived=' + this.state.turnNumber +
            '&unitsKilled=' + this.state.enemiesKilled +
            '&pointsEarned=' + this.state.pointsEarned +
            '&checkKey=' + checkKey +
            '&_token=' + $('#laravel-token').val()
            //callBack : function(){Ajax.linkLoadingEnd(link)}
        });
    }


    render() {
        return (
        <div>
            <div className="hidden-md hidden-lg text-center">Swipe your units to set direction</div>
            <div className="game">
                <BonusesContainer radius={ FIELD_RADIUS } cellSize={ this.state.cellSize } margin={ MARGIN }
                                  bonuses={ this.state.bonuses } />
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
        </div>
        );
    }
}

export default Game