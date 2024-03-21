<template>
    <!--top bar-->
    <v-nav class="top"></v-nav>

    <div class="home">
        <router-view></router-view>
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
    min-height: 5vh;
}
</style>
