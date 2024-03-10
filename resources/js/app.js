import { createApp } from "vue";
import { router } from "./config/rutes";
import { $server, $host } from "./config/axios";
import * as bootstrap from "bootstrap";
import { $echo, $channels } from "./config/echo";

import App from "./App.vue"; 

/**
 * Cheking Routes from VueRouter
 */
router.beforeEach((to, from, next) => {
    /**
     * checking for valid credentials
     */
    if (to.meta.auth) {
        $server
            .get("/api/gateway/check-authentication")
            .then((res) => {
                /**
                 * redirect user to the home page
                 */
                next();
            })
            .catch((err) => {
                /**
                 * Has a not valid crdential redirect to le login
                 */
                return next({ name: "login" });
            });
    } else {
        /**
         * if the route is no auth route pass next request
         */
        next();
    }
});
/**
 * creating Vue App
 */
const app = createApp(App);

/**
 * Global properties for Vuejs
 */
//app.config.globalProperties.$id = res.data.id;
app.config.globalProperties.$server = $server;
app.config.globalProperties.$host = $host;
app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;

/**
 * Global Components for Vuejs
 */
/* components.forEach((index) => {
            app.component(index[0], index[1]);
        });
*/
/**
 * Routes from vueRoute
 */
app.use(router);

/**
 * Mounting App
 */
app.mount("#app");
