<template>
    <div class="content">
        <router-view></router-view>
    </div>
    <v-footer></v-footer>
</template>
<script>
import VHome from "./dashboard/Index.vue";
import VFooter from "./dashboard/Footer.vue";

export default {
    components: {
        VHome,
        VFooter,
    },
};
</script>
<style lang="scss">
.content {
    flex: 1;
    margin: 0;
    padding: 0;
}
</style>
