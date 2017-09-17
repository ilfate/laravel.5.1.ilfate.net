import React from 'react';
import { render } from 'react-dom';
import { Provider } from "react-redux";
import { BrowserRouter, Route, Switch } from 'react-router-dom';
import store from './store';
import Clicker from './Clicker';
import MathEffect from './MathEffect';

const Routes = {
    CLICKER: 'clicker',
    MATH_EFFECT: 'MathEffect',
};

class App extends React.Component {
    render () {
        return (
            <Provider store={store}>
                <BrowserRouter>
                    <Switch>
                        <Route exact path={ '/' + Routes.CLICKER } component={ Clicker }/>
                        <Route path={ '/' + Routes.MATH_EFFECT } component={ MathEffect }/>
                    </Switch>
                </BrowserRouter>
            </Provider>
        );
    }
}

render(<App/>, document.getElementById('react-app'));