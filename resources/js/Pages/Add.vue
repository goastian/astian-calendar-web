<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn btn-sm m-0 btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#editor-create"
        >
            Add new task
        </button>

        <!-- Modal -->
        <div
            class="modal fade"
            id="editor-create"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1
                            class="modal-title text-color fs-5"
                            id="exampleModalLabel"
                        >
                            Add new date for calendar
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
                                <label for="subject" class="text-color"
                                    >Subject</label
                                >
                                <input
                                    id="subject"
                                    type="text"
                                    v-model="form.subject"
                                    class="form-control form-control-sm"
                                    placeholder="Subject ..."
                                />
                                <v-error :error="errors.subject"></v-error>
                            </div>
                            <div class="box">
                                <label for="resource" class="text-color"
                                    >Resource</label
                                >
                                <input
                                    type="text"
                                    v-model="form.link"
                                    class="form-control form-control-sm"
                                    placeholder="https://resource.domain.com"
                                />
                                <v-error :error="errors.link"></v-error>
                            </div>
                            <div class="box">
                                <label class="text-color">Start</label>
                                <v-date-picker
                                    v-model="time.start"
                                ></v-date-picker>
                                <v-error :error="errors.start"></v-error>
                            </div>

                            <div class="box">
                                <label class="text-color">End</label>
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
                                    <label class="text-color" for="public">
                                        Make public
                                    </label>
                                </div>
                            </div>

                            <!--Editor-->
                        </div>

                        <div class="editor">
                            <div id="create-task"></div>

                            <button class="btn btn-primary mt-3" @click="save">
                                Save note <i class="bi bi-floppy-fill mx-2"></i>
                            </button>

                            <p
                                class="text-primary mt-2"
                                v-show="errors.message"
                            >
                                {{ errors.message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["storeEvent"],

    data() {
        return {
            quill: null,
            errors: {
                message: null,
            },
            time: {},
            form: {
                subject: null,
                description: null,
                start: null,
                end: null,
                link: null,
                public: null,
            },
            button: null,
        };
    },

    mounted() {
        this.loadEditor();
    },

    methods: {
        loadEditor() {
            const toolbarOptions = [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline", "strike", "link"], // toggled buttons
                ["blockquote", "code-block"],

                [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],

                [{ color: [] }, { background: [] }], // dropdown with defaults from theme

                [{ align: [] }],

                ["clean"], // remove formatting button
            ];

            this.quill = new Quill("#create-task", {
                placeholder: "Add description for this task",
                modules: {
                    toolbar: toolbarOptions,
                },
                theme: "snow",
            });
        },
        /**
         * set format Y-m-d H:i
         */
        setDateFormart(time) {
            try {
                const year = time.getFullYear();
                const month = time.getMonth() + 1;
                const day = time.getDate();
                const hours = time.getHours();
                const minutes = time.getMinutes();

                // Format the components into the desired format
                const formattedDate = `${year}-${
                    month < 10 ? "0" : ""
                }${month}-${day < 10 ? "0" : ""}${day} ${
                    hours < 10 ? "0" : ""
                }${hours}:${minutes < 10 ? "0" : ""}${minutes}`;

                return formattedDate;
            } catch (TypeError) {}
        },

        /**
         *  save data in the bd
         * @param {*} $event
         */
        save($event) {
            this.button = $event.target;
            this.button.disabled = true;

            this.form.description = this.quill.root.innerHTML;

            this.form.start = this.setDateFormart(this.time.start);
            this.form.end = this.setDateFormart(this.time.end);

            this.$host
                .post("/api/calendars", this.form)
                .then((res) => {
                    this.button.disabled = false;
                    this.errors = {};
                    this.form = {};
                    this.time = {};
                    this.quill.root.innerHTML = "";
                    this.$emit("storeEvent", res.data.data);
                })
                .catch((err) => {
                    this.button.disabled = false;

                    if (err.response && err.response.status == 403) {
                        this.errors.message = "Don't you have rights.";
                    }
                    if (err.response && err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },
    },
};
</script>

<style scoped lang="scss">
.inputs {
    display: flex;
    flex-wrap: wrap;
}

.box {
    flex: 1 1 100%;
    margin: 1%;

    @media (min-width: 800px) {
        flex: 1 1 45%;
        margin: 1%;
    }
}

#create-task {
    min-height: 20vh;
}
</style>
