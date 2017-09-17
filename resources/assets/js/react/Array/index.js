

export const array_rand = (array) =>
{
    return array[Math.floor(Math.random()*array.length)];
}


export const createMap = (array) => {
    let map = {};
    array.map(unit => {
        const { x, y } = unit;
        if (typeof map[y] === "undefined") map[y] = {};
        if (typeof map[y][x] === "undefined") map[y][x] = unit;
        else {
            console.log('Error creating map');
        }
    });
    return map;
};