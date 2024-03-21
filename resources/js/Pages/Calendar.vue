<template>
    <div class="calendar">
        <div class="box">
            <div>
                <slot name="register"></slot>
            </div>

            <div class="today mt-2">
                <p class="text-color bolder p-0 m-0">Today</p>

                <slot
                    name="today"
                    :item="filterEvents(current_date, false)"
                ></slot>
            </div>

            <div class="tomorrow">
                <p class="text-color bolder p-0 m-0">Tomorrow</p>

                <slot
                    name="tomorrow"
                    :item="filterEvents(next_date, false)"
                ></slot>
            </div>
        </div>
        <div class="box">
            <div class="head">
                <div class="year">
                    <span @click="setPreviousYear()"
                        ><i class="bi bi-caret-left h4"></i
                    ></span>
                    <span class="text-color bolder" @click="setCurrentYear()">
                        Year {{ getCurrentYear }}</span
                    >
                    <span @click="setNextYear()"
                        ><i class="bi bi-caret-right h4"></i
                    ></span>
                </div>
                <div class="month">
                    <span @click="setPreviousMonth()"
                        ><i class="bi bi-arrow-left h4"></i
                    ></span>
                    <span class="text-color bolder" @click="setCurrentMonth()">
                        {{ month_name[getCurrentMonth] }}
                    </span>
                    <span @click="setNextMonth()"
                        ><i class="bi bi-arrow-right h4"></i
                    ></span>
                </div>
                <!-- <div class="mode">
                    <span><i class="bi bi-grid h3"></i></span>
                    <span><i class="bi bi-list-check h3"></i></span>
                </div>-->
            </div>
            <div class="days-of-week">
                <div
                    class="week text-color fw-bold"
                    v-for="(item, index) in days_name"
                    :key="index"
                >
                    {{ item }}
                </div>
            </div>
            <div class="days-of-month">
                <div
                    class="day text-sm"
                    v-for="(item, index) in days_of_month"
                    :key="index"
                >
                    <p>
                        <span
                            v-if="item.day"
                            style="cursor: pointer"
                            class="align-middle"
                            :class="{ icon: current_date == item.time }"
                            @click="setEventByDay(item.time)"
                        >
                            {{ item.day }}
                        </span>
                    </p>
                    <slot
                        name="event"
                        :item="filterEvents(item.time)"
                        :day="item"
                    ></slot>
                </div>
            </div>
            <div class="details">
                <slot name="details" :item="filter_events_by_day"></slot>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        events: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            days_name: [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ],
            month_name: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
            days_of_month: [],
            weeks_of_month: [],
            current_year: new Date().getFullYear(),
            current_month: new Date().getMonth(),
            current_day: new Date().getDate(),
            first_day_of_month: null,
            last_day_of_month: null,
            filter_events_by_day: [],
            current_date: null,
            next_date: null,
        };
    },

    mounted() {
        this.setCurrentDate();
        this.setNextDate();
    },

    computed: {
        /**
         * get current year
         */
        getCurrentYear() {
            return this.current_year;
        },

        /**
         * get current month
         */
        getCurrentMonth() {
            this.setDaysOfMonth();
            this.setDaysOfPreviousMonth();
            return this.current_month;
        },
    },

    methods: {
        /**
         * Search data in array
         * @param {*} time
         * @param {*} deleted
         */
        filterEvents(time, deleted = true) {
            const result = [];

            if (deleted) {
                try {
                    this.$props.events.forEach((element) => {
                        element.start.match(new RegExp(time, "g"))
                            ? result.push(element)
                            : null;
                    });

                    return result;
                } catch (TypeError) {}
            }

            try {
                this.$props.events.forEach((element) => {
                    element.start.match(new RegExp(time, "g")) &&
                    !element.deleted
                        ? result.push(element)
                        : null;
                });

                return result;
            } catch (error) {}
        },

        /**
         * Set all events by day
         * @param {*} time
         */
        setEventByDay(time) {
            this.filter_events_by_day = [];
            this.filter_events_by_day = this.filterEvents(time, false);
        },

        /**
         * Get all events by day
         */
        getEventsByDay() {
            return this.filter_events_by_day;
        },

        /**
         * Set the current date
         */
        setCurrentDate() {
            const date = new Date();

            const year = date.getFullYear();
            const mnth =
                date.getMonth() + 1 < 10
                    ? `0${date.getMonth() + 1}`
                    : date.getMonth() + 1;
            const day =
                date.getDate() < 10 ? `0${date.getDate()}` : date.getDate();

            this.current_date = `${year}-${mnth}-${day}`;
        },

        setNextDate() {
            const now = new Date();

            const next = new Date(now);
            next.setDate(now.getDate() + 1);

            const año = next.getFullYear();
            const mes = String(next.getMonth() + 1).padStart(2, "0");
            const dia = String(next.getDate()).padStart(2, "0");

            this.next_date = `${año}-${mes}-${dia}`;
        },

        /**
         * Get the current month of the year
         */
        setCurrentMonth() {
            this.current_month = new Date().getMonth();
            this.current_year = new Date().getFullYear();
        },

        /**
         * Get the next month of the year
         */
        setNextMonth() {
            this.current_month += 1;
            if (this.current_month > 11) {
                this.current_month = 0;
                this.current_year += 1;
            }
        },

        /**
         * Get the Previous month of the year
         */
        setPreviousMonth() {
            this.current_month -= 1;
            if (this.current_month < 0) {
                this.current_month = 11;
                this.current_year -= 1;
            }
        },

        /**
         * Get the current year
         */
        setCurrentYear() {
            this.current_year = new Date().getFullYear();
        },

        /**
         * Get the next year
         */
        setNextYear() {
            this.current_year += 1;
        },

        /**
         * Get the previous year
         */
        setPreviousYear() {
            this.current_year -= 1;
        },

        /**
         * Get all day from selected month
         */
        setDaysOfMonth() {
            const current = new Date(
                this.current_year,
                this.current_month + 1,
                0
            );
            this.days_of_month = [];

            for (let i = 1; i <= current.getDate(); i++) {
                const month = new Date(
                    this.current_year,
                    this.current_month,
                    i
                );

                const year = month.getFullYear();
                const mnth =
                    month.getMonth() + 1 < 10
                        ? `0${month.getMonth() + 1}`
                        : month.getMonth() + 1;
                const day =
                    month.getDate() < 10
                        ? `0${month.getDate()}`
                        : month.getDate();

                this.days_of_month.push({
                    day: month.getDate(),
                    name: this.days_name[month.getDay()],
                    index: month.getDay(),
                    month_name: this.month_name[month.getMonth()],
                    time: `${year}-${mnth}-${day}`,
                });

                this.first_day_of_month = this.days_of_month[0];
                this.last_day_of_month =
                    this.days_of_month[this.days_of_month.length - 1];
            }
        },

        /**
         * Get the first day of the month
         */
        getFirstDayOfMonth() {
            return this.first_day_of_month;
        },

        /**
         * Get the last day of the month
         */
        getLastDayOfMonth() {
            return this.last_day_of_month;
        },

        /**
         * Get the last days from the previous month
         */
        setDaysOfPreviousMonth() {
            const month = [];

            const current = new Date(
                this.current_year,
                this.current_month,
                0
            ).getDate();

            for (
                let i = current - (this.getFirstDayOfMonth().index - 1);
                i < current + 42 - (this.getFirstDayOfMonth().index - 1);
                i++
            ) {
                const date = new Date(
                    this.current_year,
                    this.current_month - 1,
                    i
                );

                const year = date.getFullYear();
                const mnth =
                    date.getMonth() + 1 < 10
                        ? `0${date.getMonth() + 1}`
                        : date.getMonth() + 1;
                const day =
                    date.getDate() < 10 ? `0${date.getDate()}` : date.getDate();

                month.unshift({
                    day: date.getDate(),
                    name: this.days_name[date.getDay()],
                    index: date.getDay(),
                    month_name: this.month_name[date.getMonth()],
                    time: `${year}-${mnth}-${day}`,
                });
            }
            this.days_of_month = [];
            month.forEach((element) => {
                this.days_of_month.unshift(element);
            });
        },
    },
};
</script>
<style lang="scss" scoped>
.calendar {
    width: 100%;

    @media (min-width: 940px) {
        display: flex;
    }
}

.box:first-child {
    width: 100%;
    padding: 2%;

    @media (min-width: 940px) {
        width: 30%;
        padding: 1%;
        min-height: 100vh;
        overflow-y: scroll;
    }
}

.box:last-child {
    width: 100%;
    @media (min-width: 940px) {
        width: 70%;
        padding: 1%;
    }
}

.days-of-week {
    display: flex;
}

.days-of-week div {
    width: calc(100% / 7);
    text-align: center;
    padding: 0.2%;
}

.days-of-month {
    display: flex;
    flex-wrap: wrap;
}

.day {
    width: calc(100% / 7);
    text-align: center;
    padding: 0.5% 0.2%;
}

.head {
    display: flex;
}

.head div {
    width: calc(100% / 2);
    text-align: center;
}

.head div span {
    display: inline-block;
    margin: 1% 2%;
    padding: 1% 2%;
    cursor: pointer;
}

.head i {
    color: var(--primary);
}

.icon {
    background-color: var(--calendar-day-bg);
    color: var(--calendar-day-color);
    padding: 4% 6%;
    border-radius: 55%;
}

.week {
    border-left: 1px solid var(--primary);
    border-bottom: 1px solid var(--primary);
    border-top: 1px solid var(--primary);
    overflow: hidden;
    font-size: 9px;
    @media (min-width: 940px) {
        font-size: 12px;

        overflow: visible;
    }
}

.day {
    border-bottom: 1px solid var(--primary);
    border-left: 1px solid var(--primary);
    padding: 1%;
}

.details {
    width: 100%;
    padding-left: 2%;
}
</style>
