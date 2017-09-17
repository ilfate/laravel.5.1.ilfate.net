import { createMap } from '../../Array';

export const moveEveryUnit = function moveUnits (units, radius) {
    return units.map(unit => {
        switch(unit.d) {
            case 0: if(unit.y === - (radius - 1)) { unit.d = -1; break; } unit.y--; break;
            case 1: if(unit.x === radius - 1) { unit.d = -1; break; } unit.x++; break;
            case 2: if(unit.y === radius - 1) { unit.d = -1; break; } unit.y++; break;
            case 3: if(unit.x === - (radius - 1)) { unit.d = -1; break; } unit.x--; break;
        }
        if (unit.d !== -1) {
            unit.notMovingTurns = 0;
            unit.decayTurnLimit = 4;
        }
        return unit;
    });
};

export const updateUnitsPower = function moveUnits (units) {
    return units.map(unit => {
        console.log(unit);
        if (unit.d !== -1 || (unit.x === 0 && unit.y === 0)) {
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
    let map = {};
    units.map(unit => {
        const { x, y } = unit;
        if (typeof map[y] === "undefined") map[y] = {};
        if (typeof map[y][x] === "undefined") map[y][x] = unit;
        else {
            map[y][x].power += unit.power;
            if (map[y][x].d === -1) {
                // first one is staying still
                map[y][x].d = unit.d;
            }
        }
    });
    let newUnits = [];
    for (let y in map) {
        for(let x in map[y]) {
            newUnits.push(map[y][x]);
        }
    }
    return newUnits;
};

export const resolveCollisionsWithEnemies = function resolveCollisions (units, enemies) {
    let newEnemies = [];
    let newUnits = units.map(unit => {
         newEnemies = enemies.map(enemy => {
            if (unit.x === enemy.x && unit.y === enemy.y) {
                // yea we have a collision here
                if (unit.power > enemy.power) {
                    enemy.deleted = true;
                    unit.power -= enemy.power;
                } else if (unit.power === enemy.power) {
                    enemy.deleted = true;
                    unit.deleted = true;
                } else {
                    unit.deleted = true;
                    enemy.power -= unit.power;
                }
            }
            return enemy;
        });
         return unit;
    });
    return { newUnits, newEnemies }
};

export const clearDead = function clearDead(units) {
    return units.filter(unit => !unit.deleted );
};
