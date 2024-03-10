<template>
    <!--top bar-->
    <v-nav class="top" @expand="taggleLefbar" :status="toggle_lef_bar"></v-nav>

    <div class="home">
        <!--left bar-->
        <v-left-bar
            class="left"
            v-show="!toggle_lef_bar"
            @selected-menu="taggleLefbar"
        ></v-left-bar>

        <!--Content or body-->
        <div
            :class="{
                'content-body': true,
                'body-expand': toggle_lef_bar,
                body: !toggle_lef_bar,
            }"
        >
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import VNav from "./Navbar.vue";
import VLeftBar from "./Leftbar.vue";

export default {
    components: {
        VNav,
        VLeftBar,
    },

    data() {
        return {
            toggle_lef_bar: false,
        };
    },

    methods: {
        taggleLefbar(event) {
            this.toggle_lef_bar = event;
        },
    },
};
</script>

<style scoped lang="scss">
.top {
    background-color: var(--nav-top-bg);
    color: var(--nav-top-color);
}

.home {
    display: flex; 
}

.left {
    flex: auto;
    flex-direction: row;
    background-color: var(--nav-left-bg);
    color: var(--nav-left-color);
    overflow-y: scroll;
    padding: 0;
    margin: 0; 
    min-height: 84vh;

    @media (min-width: 850px) {
        flex: 0 0 20%;
    }
}

.content-body {
    flex: auto;
    margin: 0;
    padding: 0.3%;
    overflow-y: scroll;
}

.body {
    @media (min-width: 240px) {
        display: none;
    }

    @media (min-width: 800px) {
        display: block;
    }
}

.body-expand {
    width: 100%;
}
</style>
