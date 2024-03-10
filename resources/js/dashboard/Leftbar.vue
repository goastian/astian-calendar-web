<template>
    <aside class="side">
        <ul>
            <li>
                <i class="bi bi-journal-bookmark m-3"></i>
                Calendar
            </li>
        </ul>
        <ul>
            <li>ll</li>
        </ul>
    </aside>
</template>
<script>
export default {
    emits: ["selectedMenu"],

    data() {
        return {
            tags: {},
            app_name: process.env.MIX_APP_NAME,
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
    },

    methods: {
        screenIsChanging() {
            if (window.innerWidth < 940) {
                this.$emit("selectedMenu", window.innerWidth < 940);
            }
        },

        getTags() {
            this.$host
                .get("/api/tags", { params: { per_page: 500 } })
                .then((res) => {
                    this.tags = res.data.data;
                })
                .catch((err) => {
                    if (err.response) {
                        this.errors.message = err.response.data.message;
                    }
                });
        },

        search(id) {
            this.isClicked();
        },

        isClicked() {
            if (window.innerWidth < 940) {
                this.$emit("selectedMenu", window.innerWidth < 940);
            }
        },
    },
};
</script>

<style scoped lang="scss">
.side {
    padding-top: 3%;
    background-color: inherit;
    color: inherit;
}

.side ul:nth-child(1) {
    padding-left: 1%;
    list-style: none;
    border-bottom: 2px solid var(--primary);
    padding-bottom: 4%;
}

.side ul:nth-child(2) {
    list-style: none;
    padding-left: 5%;
}
.side ul:nth-child(2) ul {
    list-style: none;
    padding-left: 8%;
}

.side a {
    color: var(--first-color);
}
</style>
