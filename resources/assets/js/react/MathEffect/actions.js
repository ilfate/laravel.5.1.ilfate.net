/**
 * Created by ilya on 02.09.17.
 */
import axios from "axios";

export function moveUnit(id, direction) {
    return function (dispatch) {

        localStorage.setItem(LOCAL_STORAGE_SELL_REQUEST_KEY, JSON.stringify({ id }));

        // we just created sell request
        // let`s check if this customer already have an account
        dispatch(loadSellRequest(id));

        dispatch({
            type: SELL_REQUEST_CREATED,
            data: id
        });
    }
}