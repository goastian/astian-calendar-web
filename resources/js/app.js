import { createApp } from "vue";
import { router } from "./config/rutes";
import { $server, $host } from "./config/axios";
import * as bootstrap from "bootstrap";
import { $echo, $channels } from "./config/echo";
import { components } from "./config/globalComponents";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

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
                 * Checking if the route is auth
                 */
                if (to.meta.auth) {
                    next();
                } else if (!to.meta.auth && to.name == "login") {
                    /**
                     * Ckecking the user is auth and the route is
                     * login we're redirect to the user to calendar route
                     */
                    return next({ name: "calendar" });
                }
            })
            .catch((err) => {
                /**
                 * Has a not valid crdential redirect to le login
                 */
                next({ name: "login" });
            });
    } else {
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
app.config.globalProperties.$server = $server;
app.config.globalProperties.$host = $host;
app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;

/**
 * Global Components for Vuejs
 */
app.component("VDatePicker", VueDatePicker);

components.forEach((index) => {
    app.component(index[0], index[1]);
});

/**
 * Routes from vueRoute
 */
app.use(router);

/**
 * Mounting App
 */
app.mount("#app");
