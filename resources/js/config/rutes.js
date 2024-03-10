import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/login",
        name: "login",
        component: () => import("../Login/Login.vue"),
        meta: { auth: false },
    },
    {
        path: "/",
        name: "home",
        component: () => import("../dashboard/Index.vue"),
        meta: { auth: true },
        children: [
            {
                path: "/",
                name: "calendar",
                component: () => import("../Pages/Calendar.vue"),
                meta: { auth: true },
            },
        ],
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
