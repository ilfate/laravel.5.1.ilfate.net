

export const coordinatsFromAngle = (radius, angle) => {
    let result = {};
    result.x = Math.round(Math.cos(angle * Math.PI / 180) * radius);
    result.y = Math.round(Math.sin(angle * Math.PI / 180) * radius);
    return result;
};

export const rand = (min, max) =>
{
    return Math.floor(Math.random() * (max - min + 1) + min);
}