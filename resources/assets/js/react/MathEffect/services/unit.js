import { createMap, uniqueArray } from '../../Array';

export const colors = {
    1: '#8DE984',
    2: '#78DE6D',
    3: '#5FCF53',
    4: '#4ABA3D',
    5: '#30AB22',
    6: '#2C9620',
    7: '#228017',
    8: '#1A6312',
    9: '#175210',
    10: '#113D0C',
};

export const moveEveryUnit = function moveUnits (units, radius) {
    return units.map(unit => {
        switch(unit.d) {
            case 0: if(unit.y === - radius) { unit.d = -1; break; } unit.was.x = unit.x; unit.was.y = unit.y--; break;
            case 1: if(unit.x === radius) { unit.d = -1; break; } unit.was.y = unit.y; unit.was.x = unit.x++; break;
            case 2: if(unit.y === radius) { unit.d = -1; break; } unit.was.x = unit.x; unit.was.y = unit.y++; break;
            case 3: if(unit.x === - radius) { unit.d = -1; break; } unit.was.y = unit.y; unit.was.x = unit.x--; break;
        }
        if (unit.d !== -1) {
            unit.notMovingTurns = 0;
            unit.decayTurnLimit = 4;
        }
        return unit;
    });
};

export const buffCenterUnit = function buffCenterUnit (units) {
    return units.map(unit => {unit.x === 0 && unit.y === 0 && unit.power++; return unit;})
};

export const updateUnitsPower = function moveUnits (units) {
    return units.map(unit => {
        if (unit.d !== -1) {
            // unit get power if he is moving of staying in the center
            unit.power++;
        } else if (unit.d === -1 && (unit.x !== 0 || unit.y !== 0)) {
            // power decay if unit is staying on the border
            unit.turnsInactive++;
            if (unit.turnsInactive >= unit.decayTurnLimit) {
                unit.power--;
                if (unit.power > 30) {
                    unit.power--;
                }
                if (unit.power > 60) {
                    unit.power--;
                }
                unit.notMovingTurns = 0;
                if (unit.decayTurnLimit > 1) {
                    unit.decayTurnLimit--;
                }
                if (unit.power <= 0) {
                    unit.deleted = true;
                }
            }
        }

        return unit;
    });
};

export const resolveUnitCollisions = function resolveCollisions (units) {
    return units.map(unit => {
        if (unit.deleted) return unit;
        units.map(unit2 => {
            if (!unit2.deleted && unit.id !== unit2.id
                && (
                    (unit.x === unit2.x && unit.y === unit2.y)
                    || (unit.x === unit2.was.x && unit.y === unit2.was.y && unit2.x === unit.was.x && unit2.y === unit.was.y)
                )) {
                // yes it is a collision
                if (unit.power > unit2.power || (unit.power === unit2.power && unit.id < unit2.id)) {
                    unit.power += unit2.power;
                    unit2.deleted = true;
                }
            }
        });
        return unit;
    });
};

export const resolveCollisionsWithEnemies = function resolveCollisions (units, enemies) {
    let newEnemies = [];
    let collisionLocations = [];
    let enemiesKilled = 0;
    let powerDestroyed = 0;
    let newUnits = units.map(unit => {
         newEnemies = enemies.map(enemy => {
            if (!unit.deleted && !enemy.deleted && ((unit.x === enemy.x && unit.y === enemy.y)
                || (unit.x === enemy.was.x && unit.y === enemy.was.y && enemy.x === unit.was.x && enemy.y === unit.was.y))) {
                // yea we have a collision here
                // console.log('collision', unit, enemy);
                if (unit.power > enemy.power) {
                    enemy.deleted = true;
                    collisionLocations.push({ x: enemy.x, y: enemy.y });
                    unit.power -= enemy.power;
                    enemiesKilled++;
                    powerDestroyed += enemy.power;
                } else if (unit.power === enemy.power) {
                    enemy.deleted = true;
                    unit.deleted = true;
                    collisionLocations.push({ x: enemy.x, y: enemy.y });
                    collisionLocations.push({ x: unit.x, y: unit.y });
                    enemiesKilled++;
                    powerDestroyed += enemy.power;
                } else {
                    unit.deleted = true;
                    collisionLocations.push({ x: unit.x, y: unit.y });
                    enemy.power -= unit.power;
                    powerDestroyed += unit.power;
                }
            }
            return enemy;
        });
         return unit;
    });
    collisionLocations = uniqueArray(collisionLocations);
    return { newUnits, newEnemies, collisionLocations, enemiesKilled, powerDestroyed }
};

export const clearDead = function clearDead(units) {
    return units.filter(unit => !unit.deleted );
};
