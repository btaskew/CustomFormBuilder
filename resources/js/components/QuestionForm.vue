<template>
    <div>
        <cfb-form :form="form" @submitted="onSubmit">
            <div class="mt-2 form-row">

                <div class="col">
                    <input-field tag="title" label="Title" :value.sync="form.title" required></input-field>

                    <textarea-field
                        tag="help_text"
                        label="Help text for question"
                        :value.sync="form.help_text"
                        rows="2"
                    ></textarea-field>
                </div>

                <div class="col">
                    <select-field
                        tag="type"
                        label="Question type"
                        :value.sync="form.type"
                        :options="[
                            {value: 'text', text: 'Text'},
                            {value: 'email', text: 'Email'},
                            {value: 'password', text: 'Password'},
                            {value: 'textarea', text: 'Text area'},
                            {value: 'label', text: 'Label'},
                            {value: 'number', text: 'Number'},
                            {value: 'file', text: 'File upload'},
                            {value: 'url', text: 'URL'},
                            {value: 'tel', text: 'Telephone'},
                            {value: 'date', text: 'Date'},
                            {value: 'time', text: 'Time'},
                            {value: 'datetime-local', text: 'Datetime'},
                            {value: 'checkbox', text: 'Checkboxes'},
                            {value: 'radio', text: 'Radio buttons'},
                            {value: 'dropdown', text: 'Dropdown select'},
                        ]"
                        :description="form.type === 'label' ? 'Please use the help text field for the label text' : ''"
                        required
                    ></select-field>

                    <div v-if="isSelectQuestion">
                        Edit options:
                        <div class="mt-1 border border-grey rounded">
                            <div :class="form.options.length > 4 && 'form-options-scroll'">
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
                            </div>
                            <button class="btn btn-raised btn-primary m-2" @click="addOption">Add option</button>
                        </div>
                        <span class="text-danger" v-if="form.errors.has('options')" v-text="form.errors.get('options')">
                        </span>
                    </div>

                </div>

                <div class="col">
                    <checkbox-field tag="required" label="Required" :checked.sync="form.required">
                    </checkbox-field>

                    <div v-if="this.allowVisibilityRequirement">
                        <checkbox-field
                            tag="visibility-requirement"
                            label="Visibility requirement"
                            :checked.sync="form.visibility_requirement"
                        ></checkbox-field>

                        <visibility-requirement-form
                            v-if="form.visibility_requirement"
                            :form-id="this.formId"
                            :question-id="this.questionId"
                            :question.sync="form.required_question"
                            :value.sync="form.required_value"
                        >
                        </visibility-requirement-form>
                    </div>
                </div>

            </div>

            <button
                type="submit"
                class="btn btn-raised btn-primary mt"
                :disabled="loading"
                v-text="loading ? 'Loading' : 'Save question'"
            ></button>

        </cfb-form>
    </div>
</template>

<script>
    import axios from 'axios';
    import {filter} from 'lodash';
    import OptionsForm from './OptionsForm';
    import VisibilityRequirementForm from './VisibilityRequirementForm';
    import InputField from './Utils/Fields/InputField';
    import CheckboxField from './Utils/Fields/CheckboxField';
    import TextareaField from './Utils/Fields/TextareaField';
    import CfbForm from './Utils/CfbForm';
    import SelectField from './Utils/Fields/SelectField';

    export default {
        components: {
            CfbForm, OptionsForm, CheckboxField, InputField, VisibilityRequirementForm, TextareaField, SelectField
        },

        props: {
            formId: {
                type: Number
            },
            questionId: {
                type: Number
            },
            form: {
                type: Object,
                required: true
            },
            loading: {
                type: Boolean,
                required: true
            },
            allowVisibilityRequirement: {
                type: Boolean,
                default: true
            },
            isSelectQuestion: {
                type: Boolean,
                required: true
            },
        },

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
                if (!id) {
                    // User is deleting an empty option, so just remove from local data
                    this.form.options = filter(this.form.options, option => option.id !== null);
                    return;
                }

                axios.delete(`/select-options/${id}`)
                    .then(response => {
                        this.loading = false;
                        flash('Option deleted');
                        this.form.options = filter(this.form.options, option => option.id !== id);
                    }).catch(error => {
                    this.loading = false;
                    flash('Error deleting option. Please try again later', 'danger');
                });
            }
        }
    }
</script>

<style>
    .form-options-scroll {
        max-height: 335px;
        overflow-y: scroll;
    }
</style>
