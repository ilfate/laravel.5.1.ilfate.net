import React from 'react';
import Field from './containers/Field';
import EnemiesContainer from './containers/EnemiesContainer';
import UnitsContainer from './containers/UnitsContainer';
import BonusesContainer from './containers/BonusesContainer';
import EndGameScreen from './containers/EndGameScreen';
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
        let pointsEarned = this.state.pointsEarned;
        let bonuses = clearDead([...this.state.bonuses]);
        let units = clearDead([...this.state.units]);
        units = buffCenterUnit(units);
        units = moveEveryUnit(units, FIELD_RADIUS);
        let enemies = clearDead([...this.state.enemies]);
        enemies = moveEveryEnemy(enemies);
        units = updateUnitsPower(units);
        enemies = updateEnemiesPower(enemies);
        let { newUnits, newEnemies, collisionLocations, enemiesKilledThisTurn, powerDestroyed } = resolveCollisionsWithEnemies(units, enemies);
        pointsEarned += powerDestroyed + (enemiesKilledThisTurn * POINTS_PER_KILL);
        newUnits = resolveUnitCollisions(newUnits);
        newEnemies = resolveUnitCollisions(newEnemies);
        [ bonuses, newUnits, newEnemies, collisionLocations ] = resolveCollisionsWithBonuses(bonuses, newUnits, newEnemies, collisionLocations);

        if (!TEST) this.tryToCreateBonus(bonuses);
        let turnNumber = this.state.turnNumber + 1;
        let enemiesKilled = this.state.enemiesKilled + enemiesKilledThisTurn;
        this.setState({
            units: newUnits,
            enemies: newEnemies,
            turnNumber,
            enemiesKilled,
            collisionLocations,
            pointsEarned });
        if (checkLooseConditions(newEnemies)) {
            this.looseGame(turnNumber, enemiesKilled, pointsEarned);
        }
        if (!TEST) this.addEnemy();
    }

    looseGame(turns, kills, points) {
        this.setState({gameRunning: false});

        var checkKey = $('#checkKey').val();

        Ajax.json('/MathEffect/save', {
            //params : '__csrf=' + Ajax.getCSRF(),
            data: 'turnsSurvived=' + turns +
            '&unitsKilled=' + kills +
            '&pointsEarned=' + points +
            '&checkKey=' + checkKey +
            '&_token=' + $('#laravel-token').val()
            //callBack : function(){Ajax.linkLoadingEnd(link)}
        });


    }


    render() {
        return (
        <div>
            <div className="mobile-helper-text hidden-md hidden-lg text-center">Swipe your units to set direction</div>
            <div className="game">
                { !this.state.gameRunning && <EndGameScreen radius={ FIELD_RADIUS } cellSize={ this.state.cellSize }
                                                            margin={ MARGIN }
                                                            turns={ this.state.turnNumber }
                                                            kills={ this.state.enemiesKilled }
                                                            points={ this.state.pointsEarned } /> }
                <BonusesContainer radius={ FIELD_RADIUS } cellSize={ this.state.cellSize } margin={ MARGIN }
                                  bonuses={ this.state.bonuses } gameRunning={ this.state.gameRunning } />
                <EnemiesContainer radius={ FIELD_RADIUS } cellSize={ this.state.cellSize } margin={ MARGIN }
                                  enemies={ this.state.enemies } addEnemy={ this.handleAddEnemy }
                                  getNewEnemyLocation={ this.getNewEnemyLocation } gameRunning={ this.state.gameRunning } />
                <UnitsContainer radius={ FIELD_RADIUS } cellSize={ this.state.cellSize } margin={ MARGIN }
                                units={ this.state.units } addUnit={ this.handleAddUnit }
                                updateUnit={ this.handleUpdateUnit } gameRunning={ this.state.gameRunning }/>

                <Field radius={ FIELD_RADIUS }
                       cellSize={ this.state.cellSize }
                       margin={ MARGIN }
                       collisionLocations={ this.state.collisionLocations }
                       gameRunning={ this.state.gameRunning }/>

            </div>
            <div className="mobile-helper-text hidden-md hidden-lg text-center">Tap on enemy to see directions</div>
        </div>
        );
    }
}

export default Game