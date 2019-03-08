<template>
    <div>
        <div>
            Search questions: <input id="search" class="form-control ml-2" type="text" @input="searchQuestions">
        </div>

        <table class="table mt-3">
            <thead>
            <tr>
                <th scope="col" style="width:40%">Question</th>
                <th scope="col" style="width:10%">Type</th>
                <th scope="col" style="width:40%">Options</th>
                <th scope="col" style="width:10%"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="question in questionList">
                <td>{{ question.title }}</td>
                <td>{{ question.type }}</td>
                <td v-if="isSelectQuestion(question.type)">
                    <span v-for="option in question.options">
                        {{ option.value }} - {{ option.display_value }} |
                    </span>
                </td>
                <td v-else>N/A</td>
                <td><a :href="`question-bank/${question.id}/edit`">Edit</a></td>
            </tr>
            </tbody>
        </table>

        <slot v-if="!showingSearchResults" name="pagination"></slot>
    </div>
</template>

<script>
    import axios from 'axios';
    import {debounce} from 'lodash';

    export default {
        props: ['questions'],

        data() {
            return {
                'questionList': this.questions,
                'loading': false,
                'showingSearchResults': false
            }
        },

        methods: {
            isSelectQuestion(type) {
                return type === 'checkbox' || type === 'radio' || type === 'dropdown';
            },

            searchQuestions:debounce(function(e) {
                const title = e.target.value;

                if (!title || title === '') {
                    this.questionList = this.questions;
                    this.showingSearchResults = false;
                    return;
                }

                axios.get(`/question-bank/search?title=` + title)
                    .then(response => {
                        this.questionList = response.data;
                        this.showingSearchResults = true;
                    }).catch(error => {
                });
            }, 250)
        }
    }
</script>

<style>
    #search {
        width: auto;
        display: inline-block;
    }
</style>