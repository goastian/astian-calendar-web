import axios from "axios";
import Cookies from "js-cookie";

/**
 * Set config for the Authorization server
 */
export const $server = axios.create({
    baseURL: process.env.MIX_APP_SERVER,
    timeout: 10000,
    withCredentials: true,
    headers: {
        Accept: "application/json",
        Authorization: Cookies.get(process.env.MIX_ECHO_TOKEN),
    },
});

/**
 * Set config for the own API
 */
export const $host = axios.create({
    baseURL: process.env.MIX_APP_URL,
    timeout: 10000,
    withCredentials: true,
    headers: {
        Accept: "application/json",
        Authorization: Cookies.get(process.env.MIX_ECHO_TOKEN),
    },
});
