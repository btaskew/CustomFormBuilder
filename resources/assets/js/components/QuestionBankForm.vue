<template>
    <question-form
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
    import {Form} from 'dd-js-package-components';
    import QuestionForm from './QuestionForm';

    export default {
        components: {QuestionForm},
        props: ['question'],

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
            if (this.question) {
                this.form = new Form({
                    title: this.question.title,
                    type: this.question.type,
                    help_text: this.question.help_text,
                    required: this.question.required,
                    options: this.question.options
                });
                this.isNewQuestion = false;
            }
        },

        computed: {
            isSelectQuestion() {
                return this.form.type === 'checkbox' || this.form.type === 'radio' || this.form.type === 'dropdown';
            }
        },

        methods: {
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
                this.form.post(`/admin/question-bank`)
                    .then(response => {
                        this.loading = false;
                        flash('Question created');
                    }).catch(error => {
                    this.loading = false;
                    flash('Error updating question. Please try again later', 'danger');
                });
            },

            submitUpdate() {
                this.form.patch(`/admin/question-bank/${this.question.id}`)
                    .then(response => {
                        this.loading = false;
                        this.form.options = response.options;
                        flash('Question updated');
                    }).catch(error => {
                    this.loading = false;
                    flash('Error updating question. Please try again later', 'danger');
                });
            },
        }
    }
</script>
