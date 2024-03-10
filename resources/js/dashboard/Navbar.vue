<template>
    <ul class="nav">
        <li class="nav-item" @click="Expand(status)">
            <a href="#" class="btn">
                <span class="mx-2">
                    {{ app_name }}
                </span>

                <i class="bi bi-list h5"></i
            ></a>
        </li>
        <li class="nav-item ms-auto">
            <v-apps></v-apps>
        </li>

        <li class="nav-item dropdown">
            <a
                class="btn dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="true"
            >
                <i class="bi bi-bell-fill h5" @click="unreadNotification"></i>
                <span class="position-absolute badge rounded-pill bg-danger">
                    {{ unread_notifications.length }}
                    <span class="visually-hidden">Unread messages</span>
                </span>
            </a>
            <ul class="dropdown-menu expand">
                <li class="dropdown-item h5">
                    <a :href="host + '/notifications/unread'">
                        Notifications
                        <span class="badge text-bg-danger">{{
                            unread_notifications.length
                        }}</span>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li
                    class="dropdown-item p-0"
                    v-for="(item, index) in unread_notifications"
                    :key="index"
                >
                    <a
                        :href="item.recurso"
                        target="_blank"
                        @click="readNotification(item.links.read)"
                    >
                        <strong class=""
                            >{{ item.titulo }}
                            <i
                                :class="[
                                    'bi h5 mx-2',
                                    item.leido ? 'bi-eye' : 'bi-eye-slash',
                                ]"
                            ></i>
                        </strong>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown icon">
            <a
                class="btn dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="true"
            >
                {{ user.nombre }}
                <i class="bi bi-box-arrow-in-right h4 m-0"></i>
            </a>
            <ul class="dropdown-menu expand bg-light">
                <li class="dropdown-item">
                    <a :href="host"
                        ><i class="bi bi-house-lock mx-1"></i>
                        My Account
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item">
                    <a @click="logout" href="#">
                        <i class="bi bi-lock-fill mx-1"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</template>
<script>
import VApps from "./Apps.vue";

export default {
    emits: ["expand"],

    props: ["status"],

    components: {
        VApps,
    },

    data() {
        return {
            expand: false,
            notifications: {},
            unread_notifications: {},
            host: process.env.MIX_APP_SERVER,
            app_name: process.env.MIX_APP_NAME,
            user: {},
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
        this.auth();
        this.listenEvents();
    },

    methods: {
        Expand(status = false) {
            this.expand = !status;
            this.$emit("expand", this.expand);
        },

        screenIsChanging() {
            this.expand = window.innerWidth < 940;
        },

        auth() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                    this.notification();
                    this.unreadNotification();
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        this.$router.push({ name: "login" });
                    }
                });
        },

        logout() {
            this.$server
                .post("api/gateway/logout")
                .then((res) => {
                    this.$router.push({ name: "login" });
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        this.$router.push({ name: "login" });
                    }
                });
        },

        notification() {
            this.$server
                .get("api/notifications")
                .then((res) => {
                    this.notifications = res.data.data;
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        this.$router.push({ name: "login" });
                    }
                });
        },

        unreadNotification() {
            this.$server
                .get("api/notifications/unread")
                .then((res) => {
                    this.unread_notifications = res.data.data;
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        this.$router.push({ name: "login" });
                    }
                });
        },

        readNotification(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.notification();
                })
                .catch((err) => {
                    if (err.response && err.response.status == 401) {
                        this.$router.push({ name: "login" });
                    }
                });
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("PushNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("ReadNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.nav {
    background-color: inherit;
    color: inherit;
}

.expand {
    padding: 5% 30% 7% 0% !important;
}
.dropdown-item a {
    text-decoration: none;
    color: inherit;
}

.dropdown-item a:hover {
    text-decoration: dotted !important;
    color: var(--nav-top-hover-color) !important;
}

.nav-item {
    color: inherit;
    @media (min-width: 240px) {
        margin-top: 0%;
    }

    @media (min-width: 240px) {
        margin-right: 2%;
    }
}

.dropdown-item img {
    width: 15%;
}
</style>
