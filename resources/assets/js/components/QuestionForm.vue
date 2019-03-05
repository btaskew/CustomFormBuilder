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
                        <input class="form-control" type="text" v-model="form.title" id="title" name="title" required>
                        <span class="text-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')">
                        </span>
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
                        <select class="form-control" v-model="form.type" id="type" name="type" required>
                            <option value="text">Text</option>
                            <option value="email">Email</option>
                            <option value="password">Password</option>
                            <option value="textarea">Text area</option>
                            <option value="label">Label</option>
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
                        <span class="text-danger" v-if="form.errors.has('type')" v-text="form.errors.get('type')">
                        </span>
                        <span v-if="form.type == 'label'">Please use the help text field for the label text</span>
                    </div>

                    <div v-if="isSelectQuestion">
                        Edit options:
                        <div class="mt-1 border border-secondary rounded">
                            <options-form
                                    v-for="(option, key) in form.options"
                                    :key="option.id"
                                    :id="option.id"
                                    :value.sync="option.value"
                                    :display-value.sync="option.display_value"
                                    :has-value-error="optionHasError(key, 'value')"
                                    :has-display-value-error="optionHasError(key, 'display_value')"
                                    @deleteOption="deleteOption"
                            >
                            </options-form>
                            <button class="btn btn-raised btn-primary m-2" @click="addOption">Add option</button>
                        </div>
                        <span class="text-danger" v-if="form.errors.has('options')" v-text="form.errors.get('options')">
                        </span>
                    </div>

                </div>

                <div class="col">
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
                    <div class="form-check form-group">
                        <input type="checkbox"
                               id="visibility-requirement"
                               name="visibility-requirement"
                               class="form-check-input"
                               :checked="hasVisibilityRequirement"
                               @change="toggleVisibilityRequirement"
                               :true-value="true"
                               :false-value="false"
                        >
                        <label for="type" class="form-check-label">
                            Visibility requirement
                        </label>
                    </div>

                    <visibility-requirement-form
                            v-if="hasVisibilityRequirement"
                            :form-id="this.formId"
                            :question-id="this.questionId"
                            :question.sync="form.required_if.question"
                            :value.sync="form.required_if.value"
                    >
                    </visibility-requirement-form>
                </div>

            </div>

            <button type="submit"
                    class="btn btn-raised btn-primary mt"
                    :disabled="loading"
                    v-text="loading ? 'Loading' : 'Save question'"
            ></button>

        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    import {filter} from 'lodash';
    import OptionsForm from './OptionsForm';
    import VisibilityRequirementForm from './VisibilityRequirementForm';

    export default {
        components: {VisibilityRequirementForm, OptionsForm},
        // TODO Can remove questionId prop after changing delete select option link
        props: [
            'formId',
            'questionId',
            'form',
            'loading',
            'success',
            'error',
            'hasVisibilityRequirement',
            'isSelectQuestion'
        ],

        methods: {
            onSubmit() {
                this.$emit('formSubmitted');
            },

            addOption(e) {
                e.preventDefault();
                this.form.options.push({id: null, value: '', display_value: ''});
            },

            optionHasError(key, field) {
                return this.form.errors.has(`options.${key}.${field}`);
            },

            deleteOption(id) {
                // TODO move up once route changed
                if (!id) {
                    // User is deleting an empty option, so just remove from local data
                    this.form.options = filter(this.form.options, option => option.id !== null);
                    return;
                }

                axios.delete(`/forms/${this.formId}/questions/${this.questionId}/options/${id}`)
                    .then(response => {
                        this.loading = false;
                        flash('Option deleted');
                        this.form.options = filter(this.form.options, option => option.id !== id);
                    }).catch(error => {
                    this.loading = false;
                    flash('Error deleting option. Please try again later', 'danger');
                });
            },

            toggleVisibilityRequirement() {
                if (this.hasVisibilityRequirement) {
                    return this.form.required_if = {
                        question: null,
                        value: null
                    };
                }

                this.form.required_if = {};
            }
        }
    }
</script>