<template>
    <div>
        <edit-question
                v-for="question in displayQuestions"
                :question="question"
                :key="question.id"
                :is-open="visibleQuestion === question.id"
                :form-id="formId"
                @toggled="onToggle"
                @questionDeleted="removeQuestion"
        >
        </edit-question>
    </div>
</template>

<script>
    import {filter} from 'lodash';
    import EditQuestion from './EditQuestion';

    export default {
        props: {
            questions: { default: [] },
            formId: {}
        },

        components: {EditQuestion},

        data() {
            return {
                visibleQuestion: null,
                displayQuestions: this.questions
            };
        },

        methods: {
            onToggle(id) {
                if (this.visibleQuestion === id) {
                    return (this.visibleQuestion = null);
                }
                this.visibleQuestion = id;
            },

            removeQuestion(id) {
                this.displayQuestions = filter(this.displayQuestions, question => {
                    return question.id !== id
                });
            }
        }
    }
</script>

<style scoped>

</style>