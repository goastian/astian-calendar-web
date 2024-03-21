<template>
    <div>
        <div class="content">
            <router-view></router-view>
        </div>
        <v-footer></v-footer>
    </div>
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
<style lang="scss" scoped>
.content {
    flex: 1;
    overflow-y: scroll;
    min-height: 92vh;
}
</style>
