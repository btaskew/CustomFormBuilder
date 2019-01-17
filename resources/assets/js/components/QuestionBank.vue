<template>
    <div class="mt-3">
        <table class="table">
            <thead>
            <tr>
                <th scope="col" style="width:5%">Include</th>
                <th scope="col" style="width:35%">Question</th>
                <th scope="col" style="width:10%">Type</th>
                <th scope="col" style="width:35%">Options</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="question in this.questions">
                <td>
                    <input
                        type="checkbox"
                        :value="question.id"
                        :id="question.id"
                        v-model="questionsToAdd"
                    >
                </td>
                <td>{{ question.title }}</td>
                <td>{{ question.type }}</td>
                <td v-if="isSelectQuestion(question.type)">
                    <span v-for="option in question.options">
                        {{ option.value }} - {{ option.display_value }} |
                    </span>
                </td>
                <td v-else="isSelectQuestion(question.type)">N/A</td>
            </tr>
            </tbody>
        </table>
        <button class="btn btn-primary"
                @click="addQuestions"
                :disabled="loading"
                v-text="loading ? 'Loading' : 'Add questions to form'"
        ></button>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        props: ["questions", "formId"],

        data() {
            return {
                'questionsToAdd': [],
                'loading': false
            }
        },

        methods: {
            isSelectQuestion(type) {
                return type === 'checkbox' || type === 'radio' || type === 'dropdown';
            },

            addQuestions() {
                // TODO validate if array empty
                this.loading = true;
                const data = {
                    'questions': this.questionsToAdd
                };

                axios.post(`/forms/${this.formId}/questions/bank`, data)
                    .then(response => {
                        this.loading = false;
                        flash("Questions added to form");
                    }).catch(error => {
                        this.loading = false;
                        flash("Error adding questions. Please try again later");
                });
            }
        }
    }
</script>

<style>
</style>