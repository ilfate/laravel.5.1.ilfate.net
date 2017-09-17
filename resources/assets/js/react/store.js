import {createStore, combineReducers} from "redux";

import { reducer as mathEffectReducer } from './MathEffect/reducer';


const appReducer = combineReducers({
    mathEffect: mathEffectReducer
});

export default createStore(
    appReducer
);
