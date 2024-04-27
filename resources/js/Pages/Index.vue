<template>
    <v-calendar :events="events">
        <template #register>
            <v-add @store-event="getEvents"></v-add>
        </template>
        <template #today="slotProps">
            <ul>
                <li
                    v-for="(item, index) in slotProps.item"
                    :key="index"
                    :class="{ 'line-through': item.deleted }"
                >
                    <span class="text-color text-sm"
                        >Start : {{ item.start }}</span
                    >
                    |
                    <span class="text-color text-sm">
                        End : {{ item.end }}</span
                    >
                    <span
                        class="float-end text-danger"
                        :class="{ hide: item.deleted }"
                        style="cursor: pointer"
                        @click="destroyEvent(item.links.destroy)"
                        ><i class="bi bi-trash-fill"></i>
                    </span>
                    <span class="text-color text-sm d-block bolder">
                        Subject : {{ item.subject }}
                    </span>
                    <a v-if="item.link" :href="item.link">Recurso</a>
                    <span class="text-color text-sm">
                        Registered : {{ item.created }}</span
                    >
                </li>
            </ul>
        </template>
        <template #tomorrow="slotProps">
            <ul>
                <li
                    v-for="(item, index) in slotProps.item"
                    :key="index"
                    :class="{ 'line-through': item.deleted }"
                >
                    <span class="text-color text-sm"
                        >Start : {{ item.start }}</span
                    >
                    |
                    <span class="text-color text-sm">
                        End : {{ item.end }}</span
                    >
                    <span
                        class="float-end text-danger"
                        :class="{ hide: item.deleted }"
                        style="cursor: pointer"
                        @click="destroyEvent(item.links.destroy)"
                        ><i class="bi bi-trash-fill"></i>
                    </span>
                    <span class="text-color text-sm d-block bolder">
                        Subject : {{ item.subject }}
                    </span>
                    <a v-if="item.link" :href="item.link">Recurso</a>
                    <span class="text-color text-sm">
                        Registered : {{ item.created }}</span
                    >
                </li>
            </ul>
        </template>
        <template #event="slotProps">
            <span
                v-if="slotProps.day.day"
                v-for="(item, index) in slotProps.item"
                :key="index"
            >
                <v-show :item="item"></v-show>
            </span>
        </template>
        <template #details="slotProps">
            <p class="text-color bolder border-bottom mt-3">Details</p>
            <ul>
                <li
                    v-for="(item, index) in slotProps.item"
                    :key="index"
                    :class="{ 'line-through': item.deleted }"
                >
                    <span class="text-color text-sm"
                        >Start : {{ item.start }}</span
                    >
                    |
                    <span class="text-color text-sm">
                        End : {{ item.end }}</span
                    >
                    <span
                        class="float-end text-danger"
                        :class="{ hide: item.deleted }"
                        style="cursor: pointer"
                        @click="destroyEvent(item.links.destroy)"
                        ><i class="bi bi-trash-fill"></i>
                    </span>
                    <span class="text-color text-sm d-block bolder">
                        Subject : {{ item.subject }}
                    </span>
                    <a v-if="item.link" :href="item.link">Recurso</a>
                    <span class="text-color text-sm">
                        Registered : {{ item.created }}</span
                    >
                </li>
            </ul>
        </template>
    </v-calendar>
</template>

<script>
import VAdd from "./Add.vue";
import VShow from "./Show.vue";
import VCalendar from "./Calendar.vue";

export default {
    components: {
        VAdd,
        VShow,
        VCalendar,
    },

    data() {
        return {
            params: {
                per_page: 50,
            },
            events: [],
            config: {},
            form: {},
            parmas: {},
            errors: {},
        };
    },

    mounted() {
        this.getEvents();
        this.listenEvents();
    },

    methods: {
        getEvents() {
            this.$host
                .get("/api/calendars", this.params)
                .then((res) => {
                    this.events = [];
                    res.data.data.forEach((element) => {
                        this.events.push(element);
                    });
                })
                .catch((err) => {});
        },

        destroyEvent(link) {
            const question = confirm("Are you sure to remove this event?");
            if (question) {
                this.$host
                    .delete(link)
                    .then((res) => {
                        this.getEvents();
                    })
                    .catch((err) => {
                        console.log(err.response);
                    });
            }
        },

        createCalendar() {
            this.$host
                .post("/api/calendars", form)
                .then((res) => {
                    this.getEvents();
                })
                .catch((err) => {
                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        listenEvents() {
            this.$echo
                .channel(this.$channels.ch_0())
                .listen("StoreCalendarEvent", (res) => {
                    console.log(res);
                    this.getEvents();
                });

            this.$echo
                .channel(this.$channels.ch_0())
                .listen("UpdateCalendarEvent", (res) => {
                    console.log(res);
                    this.getEvents();
                });

            this.$echo
                .channel(this.$channels.ch_0())
                .listen("DestroyCalendarEvent", (res) => {
                    console.log(res);
                    this.getEvents();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
ul {
    padding: 0 2%;
    list-style: outside;
}
</style>
