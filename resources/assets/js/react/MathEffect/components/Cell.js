import React from 'react';

class Cell extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            justCreated: true
        };
    }

    render() {
        const { x, y, size, margin, collision } = this.props;
        const style = {
            width: size,
            height: size,
            margin
        };
        const innerStyle = {
            width: size - 2,
            height: size - 2,
            margin: 1
        };
        return (
            <div onMouseEnter={ () => { this.setState({ justCreated: false }); } }
                 className={ `cell x-${ x } y-${ y } ${ collision } emptyCell ${ (x === 0 || y === 0) && `center-line` } ${ (Math.abs(x) === 1 || Math.abs(y) === 1) && `side-line` } ${ this.state.justCreated && !collision && `justCreated` }` }
                 style={ style }
            >
                <div className="inner-cell" style={ innerStyle }></div>
            </div>
        );
    }

};

export default Cell;