<template>
    <div class="mt-1 border border-secondary rounded p-2">
        <span v-if="loading">Loading possible questions...</span>

        <span v-else-if="error">Error loading questions</span>

        <span v-else-if="noQuestions">
            No questions available to select. Only questions of type radio, checkbox or dropdown can be used to set a visibility requirement against.
        </span>

        <div v-else>
            <div class="form-group">
                <label for="question">
                    Question
                </label>
                <select class="form-control"
                        type="text"
                        v-model="selectedQuestion"
                        @change="updateQuestion"
                        id="question"
                        name="question"
                        required
                >
                    <option v-for="question in questions" :value="question.id">{{ question.title }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="value">
                    Value
                </label>
                <select class="form-control"
                        type="text"
                        v-model="selectedValue"
                        @change="updateValue"
                        id="value"
                        name="value"
                        required
                >
                    <option v-for="option in selectedQuestionOptions" :value="option.value">{{ option.value }}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['formId', 'question', 'value'],

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

                const options = [{value: null}];
                const question = this.questions.find(question => question.id == this.question);
                return options.concat(question.options);
            }
        },

        created() {
            axios.get(`/forms/${this.formId}/select-questions`)
                .then(response => {
                    if (response.data.length < 1) {
                        this.noQuestions = true;
                        this.loading = false;
                        return;
                    }

                    const questions = [{id: null}];
                    this.questions = questions.concat(response.data);
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
            updateQuestion(e) {
                this.$emit('update:question', e.target.value);
                this.$emit('update:value', null);
                this.selectedValue = null;
            },

            updateValue(e) {
                this.$emit('update:value', e.target.value);
            }
        }
    }
</script>