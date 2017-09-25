import React from 'react';
import { calculateEnemyPath } from '../services/enemy';


const Path = props => {


    const { enemy, size, margin, radius } = props;
    const { x, y } = enemy;
    const cellRealSize = size + (margin * 2);
    const fullPath = calculateEnemyPath(radius, enemy);
    const renderedPath = fullPath.filter(step => {return step.x !== enemy.x || step.y !== enemy.y}).map(step => {
        let fa = 'fa ';
        switch (step.d) {
            case 0: fa += 'fa-long-arrow-up'; break;
            case 1: fa += 'fa-long-arrow-right'; break;
            case 2: fa += 'fa-long-arrow-down'; break;
            case 3: fa += 'fa-long-arrow-left'; break;
        }
        return <div key={`${step.x}_${step.y}`} className={`step ${fa}`} style={ {
            marginTop: step.y * cellRealSize,
            marginLeft: step.x * cellRealSize,
            width: size,
            height: size,
            lineHeight: size + 'px',
        } }></div>;
    });

    return (
        <div className="path">{ renderedPath }</div>
    );

};




export default Path;