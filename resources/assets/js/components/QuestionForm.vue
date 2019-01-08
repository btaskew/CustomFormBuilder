<template>
    <div>
        <form method="GET"
              @submit.prevent="onSubmit"
              @change="form.errors.clear($event)"
              @keydown="form.errors.clear($event)"
        >
            <div class="mt-2 form-row">

                <div class="col">
                    <div class="form-group">
                        <label for="title" :class="{ 'has-error': form.errors.has('title') }">
                            Title
                        </label>
                        <input class="form-control" type="text" v-model="form.title" id="title" name="title">
                        <span class="text-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span>
                    </div>

                    <div class="form-group">
                        <label for="type" :class="{ 'has-error': form.errors.has('order') }">
                            Order
                        </label>
                        <input class="form-control" type="number" v-model="form.order" id="order" name="order">
                        <span class="text-danger" v-if="form.errors.has('order')" v-text="form.errors.get('order')"></span>
                    </div>

                    <div class="form-group">
                        <label for="help_text"
                               :class="{ 'has-error': form.errors.has('help_text') }"
                        >
                            Help text for question
                        </label>
                        <textarea class="form-control"
                                  type="text"
                                  v-model="form.help_text"
                                  id="help_text"
                                  name="help_text"
                                  rows=2
                        ></textarea>
                        <span class="text-danger"
                              v-if="form.errors.has('help_text')"
                              v-text="form.errors.get('help_text')"
                        ></span>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="type" :class="{ 'has-error': form.errors.has('type') }">
                            Question type
                        </label>
                        <select class="form-control" type="text" v-model="form.type" id="type" name="type" required>
                            <option value="text">Text</option>
                            <option value="email">Email</option>
                            <option value="password">Password</option>
                            <option value="hidden">Hidden</option>
                            <option value="textarea">Text area</option>
                            <option value="number">Number</option>
                            <option value="file">File upload</option>
                            <option value="url">URL</option>
                            <option value="tel">Telephone</option>
                            <option value="date">Date</option>
                            <option value="time">Time</option>
                            <option value="datetime-local">Datetime</option>
                            <option value="checkbox">Checkboxes</option>
                            <option value="radio">Radio buttons</option>
                            <option value="dropdown">Dropdown select</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.has('type')" v-text="form.errors.get('type')"></span>
                    </div>

                    <div v-if="isSelectQuestion">
                        Edit options:
                        <div class="mt-1 border border-secondary rounded">
                            <options-form
                                    v-for="(option, key) in form.options"
                                    :key="option.id"
                                    :value.sync="option.value"
                                    :display-value.sync="option.display_value"
                                    :has-value-error="optionHasValueError(key)"
                                    :has-display-value-error="optionHasDisplayValueError(key)"
                            >
                            </options-form>
                            <button class="btn btn-raised btn-primary m-2" @click="addOption">Add option</button>
                        </div>
                    </div>

                </div>

                <div class="col col-md-2">
                    <div class="form-check form-group">
                        <input type="checkbox"

                               id="admin_only"
                               name="admin_only"
                               class="form-check-input"
                               v-model="form.admin_only"
                               :true-value="true"
                               :false-value="false"
                        >
                        <label for="type"
                               class="form-check-label"
                               :class="{ 'has-error': form.errors.has('admin_only') }"
                        >
                            Admin only field
                        </label>
                        <span class="text-danger"
                              v-if="form.errors.has('admin_only')"
                              v-text="form.errors.get('admin_only')"
                        ></span>
                    </div>
                    <div class="form-check form-group">
                        <input type="checkbox"
                               id="required"
                               name="required"
                               class="form-check-input"
                               v-model="form.required"
                               :true-value="true"
                               :false-value="false"
                        >
                        <label for="type"
                               class="form-check-label"
                               :class="{ 'has-error': form.errors.has('required') }"
                        >
                            Required
                        </label>
                        <span class="text-danger"
                              v-if="form.errors.has('required')"
                              v-text="form.errors.get('required')"
                        ></span>
                    </div>
                </div>


            </div>

            <button
                    type="submit"
                    class="btn btn-raised btn-primary mt"
                    :disabled="loading"
                    v-text="loading ? 'Loading' : 'Save question'"
            >

            </button>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from '../classes/Form';
    import OptionsForm from "./OptionsForm";

    export default {
        components: {OptionsForm},
        props: ['formId', 'questionId'],

        data() {
            return {
                form: new Form({
                    title: '',
                    type: '',
                    help_text: '',
                    required: false,
                    admin_only: false,
                    order: '',
                    options: []
                }),
                loading: false,
                success: true,
                error: false,
                newQuestion: true
            }
        },

        created() {
            if (this.questionId) {
                this.loadQuestionData(this.questionId);
            }
        },

        computed: {
            isSelectQuestion() {
                return this.form.type === 'checkbox' || this.form.type === 'radio' || this.form.type === 'dropdown';
            }
        },

        methods: {
            loadQuestionData(id) {
                this.loading = true;
                axios.get(`/forms/${this.formId}/questions/${id}`)
                    .then(response => {
                        this.form = new Form({
                            title: response.data.title,
                            type: response.data.type,
                            help_text: response.data.help_text,
                            required: response.data.required,
                            admin_only: response.data.admin_only,
                            order: response.data.order,
                            options: response.data.options
                        });
                        this.newQuestion = false;
                        this.loading = false;
                    });
            },

            onSubmit() {
                this.loading = true;

                if (this.newQuestion) {
                    return (this.submitNewQuestion());
                }

                this.submitUpdate();
            },

            submitNewQuestion() {
                this.form.post(`/forms/${this.formId}/questions`)
                    .then(response => {
                        this.loading = false;
                        window.location = `/forms/${this.formId}/questions`;
                    }).catch(error => {
                    this.loading = false;
                    flash("Error updating form. Please try again later", "danger");
                });
            },

            submitUpdate() {
                this.form.patch(`/forms/${this.formId}/questions/${this.questionId}`)
                    .then(response => {
                        this.loading = false;
                        flash("Question updated");
                        this.$emit('questionUpdated', response.title);
                    }).catch(error => {
                    this.loading = false;
                    flash("Error updating form. Please try again later", "danger");
                });
            },

            addOption(e) {
                e.preventDefault();
                this.form.options.push({id: null, value: '', display_value: ''});
            },

            optionHasValueError(key) {
                return this.form.errors.has(`options.${key}.value`);
            },

            optionHasDisplayValueError(key) {
                return this.form.errors.has(`options.${key}.display_value`);
            },
        }
    }
</script>