import { rand } from '../../Math';
import { array_rand } from '../../Array';

export const spawnBonus = function spawnBonus (bonuses, units, enemies, radius) {
    let possibleLocations = [];
    for (let y = -radius + 1; y < radius; y++) {
        for (let x = -radius + 1; x < radius; x++) {
            if ((x !== 0 || y !== 0) && units.filter(unit => unit.x === x && unit.y === y).length === 0
                && enemies.filter(enemy => enemy.x === x && enemy.y === y).length === 0
                && bonuses.filter(bonus => bonus.x === x && bonus.y === y).length === 0) {
                possibleLocations.push({x, y});
            }
        }
    }
    if (possibleLocations.length === 0) return bonuses;
    const newLocation = array_rand(possibleLocations);
    let newBonus = {
        x: newLocation.x,
        y: newLocation.y,
        deleted: false,
        power: rand(1, 5) * (rand(0, 1) === 1 ? 1 : -1),
    };
    return newBonus;
};

export const resolveCollisionsWithBonuses = function resolveCollisionsWithBonuses (bonuses, units, enemies, collisionLocations) {
    bonuses = bonuses.map(bonus => {
        units = units.map(unit => {
            if (!unit.deleted && !bonus.deleted && unit.x === bonus.x && unit.y === bonus.y) {
                // collision with unit
                unit.power += bonus.power;
                if (unit.power <= 0) {
                    unit.deleted = true;
                    collisionLocations.push({x: bonus.x, y: bonus.y});
                }
                bonus.deleted = true;
            }
            return unit;
        });
        if (bonus.deleted) return bonus;
        enemies = enemies.map(enemy => {
            if (!enemy.deleted && !bonus.deleted && enemy.x === bonus.x && enemy.y === bonus.y) {
                // collision with enemy
                enemy.power += bonus.power;
                if (enemy.power <= 0) {
                    enemy.deleted = true;
                    collisionLocations.push({x: bonus.x, y: bonus.y});
                }
                bonus.deleted = true;
            }
            return enemy;
        });
        return bonus;
    })
    return [bonuses, units, enemies, collisionLocations];
};

