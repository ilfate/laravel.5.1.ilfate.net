import React from 'react';
import { calculateEnemyPath } from '../services/enemy';


const Path = props => {


    const { enemy, size, margin } = props;
    const { x, y } = enemy;
    const cellRealSize = size + (margin * 2);
    const fullPath = calculateEnemyPath(enemy);
    const iconName = 'caret';
    let stepNumber = 0;
    const renderedPath = fullPath.filter(step => {return step.x !== enemy.x || step.y !== enemy.y}).map(step => {
        let fa = 'fa ';
        switch (step.d) {
            case 0: fa += `fa-${iconName}-up`; break;
            case 1: fa += `fa-${iconName}-right`; break;
            case 2: fa += `fa-${iconName}-down`; break;
            case 3: fa += `fa-${iconName}-left`; break;
        }
        stepNumber++;
        return <div key={`${step.x}_${step.y}`} className={`step ${fa}`} style={ {
            marginTop: step.y * cellRealSize,
            marginLeft: step.x * cellRealSize,
            width: size,
            height: size,
            lineHeight: size + 'px',
            fontSize: 30,
            animationDelay: `0.${stepNumber}s`
        } }></div>;
    });

    return (
        <div className="path">{ renderedPath }</div>
    );

};




export default Path;