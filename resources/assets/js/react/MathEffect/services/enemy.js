import { rand } from '../../Math';
import { array_rand } from '../../Array';

export const moveEveryEnemy = function moveEnemies (enemies) {
    return enemies.map(enemy => {

        switch(enemy.d) {
            case 0: enemy.was.x = enemy.x; enemy.was.y = enemy.y--; break;
            case 1: enemy.was.y = enemy.y; enemy.was.x = enemy.x++; break;
            case 2: enemy.was.x = enemy.x; enemy.was.y = enemy.y++; break;
            case 3: enemy.was.y = enemy.y; enemy.was.x = enemy.x--; break;
        }
        const [step] = calculateEnemyPath(enemy, 1);
        enemy.d = step.d;
        return enemy;
    });
};

export const updateEnemiesPower = function updateEnemiesPower (enemies) {
    return enemies.map(enemy => {
            enemy.power++;
        return enemy;
    });
};

export const generateNewEnemyLocation = function generateLocation (radius, enemies, units) {
    // ok we need a random cell, which is empty
    // let`s get all empty cells
    const min = -radius ;
    const max = radius;
    let possibleCells = [];
    for (let y = min; y <= max; y++) {
        for (let x = min; x <= max; x++) {
            if (x !== min && x !== max && y !== min && y !== max) continue;
            if (enemies.filter(e => { return e.x === x && e.y === y }).length > 0) continue;
            if (units.filter(e => { return e.x === x && e.y === y }).length > 0) continue;

            possibleCells.push({x, y});
        }
   }
    return array_rand(possibleCells);
};

const getDirectionOnCentralRoad = function(x, y) {
    if (x != 0) {
        if (x < 0) {
            return 1;
        } else {
            return 3;
        }
    } else {
        if (y < 0) {
            return 2;
        } else {
            return 0;
        }
    }
};

const getDirectionOnSideRoad = function(x, y) {
    if (x === - 1 || x === 1) {
        if (y < 0) {
            return 2;
        } else {
            return 0;
        }
    } else {
        if (x < 0) {
            return 1;
        } else {
            return 3;
        }
    }
};

export const getEnemyStartDirection = function getStartDirection(enemy, radius) {
    let possibleDirections = [];
    const min = - radius;
    const max = radius;
    if (enemy.x === min) {
        possibleDirections.push(1);
    } else if (enemy.x === max) {
        possibleDirections.push(3);
    }
    if (enemy.y === min) {
        possibleDirections.push(2);
    } else if (enemy.y === max) {
        possibleDirections.push(0);
    }
    if (possibleDirections.length > 1) {
        return array_rand(possibleDirections);
    }
    return possibleDirections[0];
};




export const calculateEnemyPath = function calculatePath (enemy, limit) {
    let path = [];
    if (enemy.x === 0 && enemy.y === 0) return [{d: -1, x: 0, y: 0}];
    let wasLocation = {x: enemy.was.x, y: enemy.was.y};
    let currentLocation = {x: enemy.x, y: enemy.y};
    let currentDirection = enemy.d;
    if (!limit) limit = 99;
    while ((currentLocation.x !== 0 || currentLocation.y !== 0) && limit > 0) {
        let move = {d: -1};
        if (currentLocation.x === 0 || currentLocation.y === 0) {
            // we are on direct path to center
            if (wasLocation.x === 0 || wasLocation.y === 0) {
                // we were already on main road. let`s keep moving
                move.d = currentDirection;
            } else {
                move.d = getDirectionOnCentralRoad(currentLocation.x, currentLocation.y);
            }
        } else if (currentLocation.x === -1 || currentLocation.x === 1 || currentLocation.y === -1
            || currentLocation.y === 1) {
            // we are on a side road
            if (wasLocation.x === -1 || wasLocation.x === 1 || wasLocation.y === -1
                || wasLocation.y === 1) {
                // we also were on a side road. lets keep current direction
                move.d = currentDirection;
            } else {
                move.d = getDirectionOnSideRoad(currentLocation.x, currentLocation.y);
            }
        } else {
            move.d = currentDirection;
        }
        if (move.d !== -1) {
            switch (move.d) {
                case 0: wasLocation.x = currentLocation.x; wasLocation.y = currentLocation.y--; break;
                case 1: wasLocation.y = currentLocation.y; wasLocation.x = currentLocation.x++; break;
                case 2: wasLocation.x = currentLocation.x; wasLocation.y = currentLocation.y++; break;
                case 3: wasLocation.y = currentLocation.y; wasLocation.x = currentLocation.x--; break;
            }
            currentDirection = move.d;
            move.x = wasLocation.x;
            move.y = wasLocation.y;
            path.push(move);
        } else {
            return path;
        }
        limit--;
    }

    return path;
}


export const tryUpgradeToBoss = function tryUpgradeToBoss (enemy, turnNumber) {
    if (turnNumber < 4) return;
    const tenthOfTurnNumber = Math.round(turnNumber / 10);
    const fifteenthOfTurnNumber = Math.round(turnNumber / 15);
    const chanceToSpawnBoss = 90 + tenthOfTurnNumber;
    if (rand(0, 100) > chanceToSpawnBoss) return;
    const minBossPower = 2 + fifteenthOfTurnNumber;
    const maxBossPower = 7 + fifteenthOfTurnNumber;
    enemy.power = rand(minBossPower,maxBossPower);
    enemy.isBoss = true;
}

export const checkLooseConditions = function checkLooseConditions (enemies) {
    return enemies.filter(e => {return e.x === 0 && e.y === 0 && !e.deleted && e.power > 0;}).length > 0;
};

