import { rand } from '../../Math';
import { array_rand } from '../../Array';

export const moveEveryEnemy = function moveUnits (enemies, radius) {
    return enemies.map(enemy => {
        switch(enemy.d) {
            case 0: enemy.y--; break;
            case 1: enemy.x++; break;
            case 2: enemy.y++; break;
            case 3: enemy.x--; break;
        }
        [enemy.d] = calculateEnemyPath(radius, enemy, 1);
        return enemy;
    });
};

export const updateEnemiesPower = function moveUnits (enemies) {
    return enemies.map(enemy => {
            enemy.power++;
        return enemy;
    });
};

export const generateNewEnemyLocation = function generateLocation (radius, enemies, units) {
    // ok we need a random cell, which is empty
    // let`s get all empty cells
    const min = -(radius - 1);
    const max = radius - 1;
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
    const min = - (radius - 1);
    const max = radius - 1;
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

export const calculateEnemyPath = function calculatePath (radius, enemy, limit) {
    let path = [];
    let currentLocation = {x: enemy.x, y: enemy.y};
    if (!limit) limit = 99;
    while ((currentLocation.x !== 0 && currentLocation.y !== 0) && limit > 0) {
        let move = -1;
        if (currentLocation.x === 0 || currentLocation.y === 0) {
            // we are on direct path to center
            move = getDirectionOnCentralRoad(currentLocation.x, currentLocation.y);
        } else if (currentLocation.x === -1 || currentLocation.x === 1 || currentLocation.y === -1
            || currentLocation.y === 1) {
            // we are on a side road
            move = getDirectionOnSideRoad(currentLocation.x, currentLocation.y);
        } else {
            move = enemy.d;
        }
        if (move !== -1) {
            path.push(move);
            switch (move) {
                case 0: currentLocation.y--; break;
                case 1: currentLocation.x++; break;
                case 2: currentLocation.y++; break;
                case 3: currentLocation.x--; break;
            }
        } else {
            return path;
        }
        limit--;
    }

    return path;
}

