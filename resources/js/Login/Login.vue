<script setup>
import { ref } from "vue";

const title = ref(process.env.MIX_APP_NAME);
const home_page = ref(process.env.MIX_HOME_PAGE);
const login = ref("/redirect");
const register = ref(process.env.MIX_HOME_REGISTER);
</script>

<template>
    <div class="container-fluid mx-0 px-0 text-color">
        <nav class="navbar navbar-light bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand text-light">
                    {{ title }} <i class="bi bi-calendar-day h4"></i>
                </a>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="btn nav-link text-light" :href="home_page"
                            >Home</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="btn nav-link text-light" :href="register"
                            >Register</a
                        >
                    </li>
                </ul>
            </div>
        </nav>
        <main class="main">
            <p v-text="title"></p>
            <p>somthing here</p>

            <a class="btn btn-primary btn-lg text-light" :href="login">Login</a>
        </main>
    </div>
</template>

<style lang="scss" scoped>
.main {
    text-align: center;
    min-height: 82vh;
    padding-top: 10vh;
}

.main .btn {
    width: 20%;
    margin-top: 2%;
}

p:nth-child(1) {
    font-size: 3em;
}

p:nth-child(2) {
    font-size: 1.5em;
    margin-top: 3%;
}
.btn:hover {
    font-weight: normal !important;
}

footer {
    font-size: 0.9em;
}

.btn-link {
    font-size: 1em !important;
}
</style>
