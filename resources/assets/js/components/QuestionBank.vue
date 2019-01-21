<template>
    <div class="mt-3">
        <div class="d-flex justify-content-between">
            <h3>Add questions from question bank</h3>
            <div>
                Search questions: <input id="search" class="form-control ml-2" type="text" @input="searchQuestions">
            </div>
        </div>

        <table class="table mt-3">
            <thead>
            <tr>
                <th scope="col" style="width:5%">Include</th>
                <th scope="col" style="width:35%">Question</th>
                <th scope="col" style="width:10%">Type</th>
                <th scope="col" style="width:35%">Options</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="question in questionList">
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
                <td v-else>N/A</td>
            </tr>
            </tbody>
        </table>
        <button class="btn btn-primary"
                @click="addQuestions"
                :disabled="loading || this.questionsToAdd.length < 1"
                v-text="loading ? 'Loading' : 'Add questions to form'"
        ></button>

        <slot v-if="!showingSearchResults" name="pagination"></slot>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        props: ["questions", "formId"],

        data() {
            return {
                'questionList': this.questions,
                'questionsToAdd': [],
                'loading': false,
                'showingSearchResults': false
            }
        },

        methods: {
            isSelectQuestion(type) {
                return type === 'checkbox' || type === 'radio' || type === 'dropdown';
            },

            addQuestions() {
                this.loading = true;
                const data = {
                    'questions': this.questionsToAdd
                };

                axios.post(`/forms/${this.formId}/questions/bank`, data)
                    .then(response => {
                        this.loading = false;
                        this.questionsToAdd = [];
                        flash("Questions added to form");
                    }).catch(error => {
                        this.loading = false;
                        flash("Error adding questions. Please try again later");
                });
            },

            searchQuestions(e) {
                const title = e.target.value;

                if (!title || title === '') {
                    this.questionList = this.questions;
                    this.showingSearchResults = false;
                    return;
                }

                axios.get(`/questions/bank/search?title=` + title)
                    .then(response => {
                        this.questionList = response.data;
                        this.showingSearchResults = true;
                    }).catch(error => {});
            }
        }
    }
</script>

<style>
    #search {
        width: auto;
        display: inline-block;
    }
</style>