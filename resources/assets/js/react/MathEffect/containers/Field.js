import React from 'react';
import Cell from './../components/Cell';



export const Field = props => {

    const { radius, cellSize, margin } = props;
    const rowLength = (radius * 2) - 1;
    const rowWidth = (cellSize + (margin * 2)) * rowLength;
    const rowStyle = { width: rowWidth };
    const fieldStyle = { width: rowWidth };

    const list = Array.from(Array(rowLength), (d, i) => {
        return Array.from(Array(rowLength), (d2, i2) => {
            let x = i2 - radius + 1;
            let y = i - radius + 1;
            return {x, y};
        });
    });

    const renderedList = list.map(row => { return <div style={ rowStyle } className="row" key={row[0].y}>{ row.map(
            ({x, y}) => <Cell key={ x + '_' + y } x={ x } y={ y } size={ cellSize } margin={ margin } />
        ) }</div>
    });

    return (
        <div style={ fieldStyle } className="field">
            { renderedList }
        </div>
    );

};

export default Field