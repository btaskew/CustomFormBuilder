<template>
    <div>
        <div v-if="displayQuestions.length < 1" class="alert alert-info animated mt" role="alert">
            No questions found for current form
        </div>

        <div v-else>
            <p>
                To change the order of questions, drag and drop them into the correct order then click the "Save order" button
            </p>

            <draggable v-model="displayQuestions" :options="dragOptions">
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
            </draggable>

            <button class="btn btn-primary mt-3" @click="saveOrder">Save order</button>

            <div v-if="loading" class="loader"></div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import {filter} from 'lodash';
    import Draggable from 'vuedraggable';
    import EditQuestion from './EditQuestion';

    export default {
        props: {
            questions: { default: [] },
            formId: {}
        },

        components: {EditQuestion, Draggable},

        data() {
            return {
                visibleQuestion: null,
                displayQuestions: this.questions,
                loading: false,
                dragOptions: {ghostClass: "ghost"}
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
            },

            saveOrder() {
                const order = [];

                this.displayQuestions.forEach((question, index) => {
                    order.push({question:question.id, order:index});
                });
                
                axios.patch(`/forms/${this.formId}/order`, {'order':order})
                    .then(response => {
                        flash("Order updated");
                        this.loading = false;
                    }).catch(error => {
                    this.loading = false;
                    flash("Error updating order. Please try again later", "danger");
                });
            }
        }
    }
</script>

<style>
    .ghost {
        opacity: 0;
    }
</style>