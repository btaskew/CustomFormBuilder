<template>
    <question-form
            :form-id="this.formId"
            :question-id="this.questionId"
            :form="this.form"
            :loading="this.loading"
            :is-new-question="this.isNewQuestion"
            :is-select-question="this.isSelectQuestion"
            @formSubmitted="this.handleSubmit"
    >
    </question-form>
</template>

<script>
    import axios from 'axios';
    import {isEqual} from 'lodash';
    import {Form} from 'dd-js-package-components';
    import QuestionForm from './QuestionForm';

    export default {
        components: {QuestionForm},

        props: ['formId', 'questionId'],

        data() {
            return {
                form: new Form({
                    title: null,
                    type: null,
                    help_text: null,
                    required: false,
                    options: [],
                    visibility_requirement: false,
                    required_question: null,
                    required_value: null
                }),
                loading: false,
                isNewQuestion: true,
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
            },

            hasVisibilityRequirement() {
                return !isEqual(this.form.required_if, {question:null, value:null});
            }
        },

        methods: {
            loadQuestionData(id) {
                this.loading = true;
                axios.get(`/forms/${this.formId}/questions/${id}`)
                    .then(response => {
                        this.fillQuestion(response.data);
                        this.isNewQuestion = false;
                        this.loading = false;
                    });
            },

            fillQuestion(question) {
                this.form = new Form({
                    title: question.title,
                    type: question.type,
                    help_text: question.help_text,
                    required: question.required,
                    options: question.options,
                    visibility_requirement: false,
                    required_question: null,
                    required_value: null
                });

                if (question.visibility_requirement) {
                    this.form.visibility_requirement = true;
                    this.form.required_question = question.visibility_requirement.required_question_id;
                    this.form.required_value =  question.visibility_requirement.required_value;
                }
            },

            handleSubmit() {
                this.loading = true;

                if (!this.isSelectQuestion) {
                    this.form.options = [];
                }

                if (this.isNewQuestion) {
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
                    flash('Error updating form. Please try again later', 'danger');
                });
            },

            submitUpdate() {
                this.form.patch(`/forms/${this.formId}/questions/${this.questionId}`)
                    .then(response => {
                        flash('Question updated');
                        this.$emit('questionUpdated', response.title);
                        this.form.options = response.options;
                        this.loading = false;
                    }).catch(error => {
                    this.loading = false;
                    flash('Error updating form. Please try again later', 'danger');
                });
            },
        }
    }
</script>