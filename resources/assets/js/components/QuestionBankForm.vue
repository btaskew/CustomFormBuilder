<template>
    <question-form
            :question-id="this.questionId"
            :form="this.form"
            :loading="this.loading"
            :is-new-question="this.isNewQuestion"
            :is-select-question="this.isSelectQuestion"
            :allow-visibility-requirement="false"
            @formSubmitted="this.handleSubmit"
    >
    </question-form>
</template>

<script>
    import axios from 'axios';
    import Form from '../classes/Form';
    import QuestionForm from './QuestionForm';

    export default {
        components: {QuestionForm},
        props: ['questionId'],

        data() {
            return {
                form: new Form({
                    title: null,
                    type: null,
                    help_text: null,
                    required: false,
                    options: []
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
            }
        },

        methods: {
            loadQuestionData(id) {
                this.loading = true;
                // TODO
            },

            mapQuestion(question) {
                this.form = new Form({
                    title: question.title,
                    type: question.type,
                    help_text: question.help_text,
                    required: question.required,
                    options: question.options
                });
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
                this.form.post(`/question-bank`)
                    .then(response => {
                        this.loading = false;
                        flash('Question created');
                    }).catch(error => {
                    this.loading = false;
                    flash('Error updating question. Please try again later', 'danger');
                });
            },

            submitUpdate() {
                // TODO
            },
        }
    }
</script>