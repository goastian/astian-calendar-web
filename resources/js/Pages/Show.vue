<template>
    <div>
        <!-- Button trigger modal -->
        <button
            @click="loadForm()"
            type="button"
            class="mt-1 badge badge-pill badge-primary bg-primary d-block"
            :class="{ 'line-through': item.deleted, 'bg-danger': item.deleted }"
            data-bs-toggle="modal"
            :data-bs-target="'#xx_'.concat(item.id.replace(/-/g, '_'))"
        >
            {{ item.subject }}
        </button>

        <!-- Modal -->
        <div
            class="modal fade"
            :id="'xx_'.concat(item.id.replace(/-/g, '_'))"
            tabindex="-1"
            aria-labelledby="editor-show"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1
                            class="modal-title text-color fs-5"
                            id="editor-show"
                        >
                            {{ item.subject }}
                        </h1>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="inputs">
                            <div class="box">
                                <label for="name" class="text-color bolder"
                                    >Subject</label
                                >
                                <input
                                    id="name"
                                    type="text"
                                    v-model="form.subject"
                                    class="form-control form-control-sm text-color"
                                    placeholder="Subject ..."
                                />
                                <v-error :error="errors.subject"></v-error>
                            </div>
                            <div class="box">
                                <label for="resource" class="text-color bolder"
                                    >Resource</label
                                >
                                <input
                                    type="text"
                                    v-model="form.link"
                                    class="form-control form-control-sm text-color"
                                    placeholder="https://resource.domain.com"
                                />
                                <v-error :error="errors.link"></v-error>
                            </div>
                            <div class="box">
                                <label class="text-color bolder">Start</label>
                                <v-date-picker
                                    v-model="time.start"
                                ></v-date-picker>
                                <v-error :error="errors.start"></v-error>
                            </div>

                            <div class="box">
                                <label class="text-color bolder">End</label>
                                <v-date-picker
                                    v-model="time.end"
                                ></v-date-picker>
                                <v-error :error="errors.end"></v-error>
                            </div>

                            <div class="box">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="public"
                                    />
                                    <label
                                        class="text-color bolder"
                                        for="public"
                                    >
                                        Make public
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="text-justify">
                            <div
                                style="min-height: 20vh"
                                :id="
                                    'editor'.concat(item.id.replace(/-/g, '_'))
                                "
                            ></div>

                            <p class="text-primary my-2" v-show="message">
                                {{ message }}
                            </p>
                            <button class="btn btn-primary mt-3" @click="save">
                                Update event<i
                                    class="bi bi-floppy-fill mx-2"
                                ></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            editor: null,
            errors: {
                message: null,
            },
            time: { start: null, end: null },
            form: {},
            button: null,
            message: null,
        };
    },

    mounted() {
        this.loadEditor();
        this.loadForm();
    },

    methods: {
        /**
         * Setting data to the form
         */
        loadForm() {
            this.form = this.$props.item;
            this.time.start = this.$props.item.start;
            this.time.end = this.$props.item.end;
            this.quill.root.innerHTML = this.$props.item.description;
        },

        /**
         * Load editor
         */
        loadEditor() {
            const toolbarOptions = [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline", "strike", "link"], // toggled buttons
                ["blockquote", "code-block"],

                [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],

                [{ color: [] }, { background: [] }], // dropdown with defaults from theme

                [{ align: [] }],

                ["clean"],
            ];

            this.quill = new Quill(
                `#editor${this.$props.item.id.replace(/-/g, "_")}`,
                {
                    placeholder: "Add description for this task",
                    modules: {
                        toolbar: toolbarOptions,
                    },
                    theme: "snow",
                }
            );
        },
        /**
         * Set format Y-m-d H:i in local time
         */
        setDateFormart(time) {
            try {
                const date = new Date(time);
                const year = date.getFullYear();
                const month = date.getMonth() + 1;
                const day = date.getDate();
                const hours = date.getHours();
                const minutes = date.getMinutes();

                // Format the components into the desired format
                const formattedDate = `${year}-${
                    month < 10 ? "0" : ""
                }${month}-${day < 10 ? "0" : ""}${day} ${
                    hours < 10 ? "0" : ""
                }${hours}:${minutes < 10 ? "0" : ""}${minutes}`;

                return formattedDate;
            } catch (TypeError) {}
        },

        save($event) {
            this.button = $event.target;
            this.button.disabled = true;
            this.message = null;

            this.form.description = this.quill.root.innerHTML;
            this.form.start = this.setDateFormart(this.time.start);
            this.form.end = this.setDateFormart(this.time.end);

            this.$host
                .put(this.form.links.update, this.form)
                .then((res) => {
                    this.button.disabled = false;
                    this.message = "Event has been updated.";
                })
                .catch((err) => {
                    if (err.response && err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }

                    this.button.disabled = false;
                });
        },
    },
};
</script>

<style scoped lang="scss">
.inputs {
    display: flex;
    flex-wrap: wrap;
    text-align: justify !important;
}

.box {
    flex: 1 1 100%;
    margin: 1%;

    @media (min-width: 800px) {
        flex: 1 1 45%;
        margin: 1%;
    }
}
</style>
