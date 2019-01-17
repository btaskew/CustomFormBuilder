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
    </div>
</template>

<script>
    export default {
        props: ["questions"],

        data() {
            return {
                'questionsToAdd': []
            }
        },

        methods: {
            isSelectQuestion(type) {
                return type === 'checkbox' || type === 'radio' || type === 'dropdown';
            },
        }
    }
</script>

<style>
</style>