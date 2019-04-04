<template>
    <div class="mt-1 border border-secondary rounded p-2">
        <span v-if="loading">Loading possible questions...</span>

        <span v-else-if="error">Error loading questions</span>

        <span v-else-if="noQuestions">
            No questions available to select. Only other questions of type radio, checkbox or dropdown can be used to
            set a visibility requirement against.
        </span>

        <div v-else>
            <dd-select-input
                    tag="question"
                    label="Question"
                    :value.sync="selectedQuestion"
                    :options="questions"
                    option-value-field="id"
                    option-text-field="title"
                    @update:value="updateQuestion"
                    show-first-option
                    required
            ></dd-select-input>

            <dd-select-input
                    tag="value"
                    label="Value"
                    :value.sync="selectedValue"
                    @update:value="updateValue"
                    :options="selectedQuestionOptions"
                    option-text-field="value"
                    show-first-option
                    required
            ></dd-select-input>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['formId', 'question', 'value', 'questionId'],

        data() {
            return {
                loading: true,
                questions: [],
                error: false,
                noQuestions: false,
                selectedQuestion: null,
                selectedValue: null
            }
        },

        computed: {
            selectedQuestionOptions() {
                if (!this.question) {
                    return [];
                }

                return this.questions.find(question => question.id == this.question).options;
            }
        },

        created() {
            axios.get(`/forms/${this.formId}/select-questions?exclude_question=${this.questionId}`)
                .then(response => {
                    if (response.data.length < 1) {
                        this.noQuestions = true;
                        this.loading = false;
                        return;
                    }

                    this.questions = response.data;
                    this.loading = false;

                    if (this.question && this.value) {
                        this.selectedQuestion = this.question;
                        this.selectedValue = this.value;
                    }

                }).catch(error => {
                this.loading = false;
                this.error = true;
            })
        },

        methods: {
            updateQuestion(value) {
                this.$emit('update:question', value);
                this.$emit('update:value', null);
                this.selectedValue = null;
            },

            updateValue(value) {
                this.$emit('update:value', value);
            }
        }
    }
</script>