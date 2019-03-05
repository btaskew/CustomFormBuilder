<template>
    <div class="card" :class="{'mb-2 mt-2 border-secondary': isOpen}">
        <div class="card-header d-flex justify-content-between" :class="{'bg-secondary text-white': isOpen}">
            {{ title }}
            <div class="d-flex control-buttons">
                <i class="fas fa-trash-alt mr-3" :class="{'text-danger': !isOpen}" @click="showConfirmModal = true"></i>
                <i class="fas fa-cog fa-lg" @click="toggleForm"></i>
            </div>
        </div>

        <div v-if="isOpen" class="card-body">
            <form-question-form :question-id="question.id" :form-id="formId" @questionUpdated="this.updateTitle">
            </form-question-form>
        </div>

        <modal v-if="showConfirmModal">
            <h4 slot="header">Confirm deletion</h4>

            <div slot="body">
                <p>Are you sure you want to delete this question?</p>

                <div class="form-group flex-column">
                    <button type="button" @click="deleteQuestion" class="btn btn-raised btn-primary">
                        Delete question
                    </button>
                    <button type="button" @click="showConfirmModal = false" class="btn btn-raised btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </modal>

        <loading-modal :show="loading"></loading-modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import Modal from './Utils/modal';
    import LoadingModal from './Utils/LoadingModal';

    export default {
        components: {LoadingModal, Modal},

        props: ['question', 'isOpen', 'formId'],

        data() {
            return {
                title: this.question.title,
                showConfirmModal: false,
                loading: false
            }
        },

        methods: {
            toggleForm() {
                this.$emit('toggled', this.question.id);
            },

            updateTitle(title) {
                this.title = title;
            },

            deleteQuestion() {
                this.showConfirmModal = false;
                this.loading = true;
                const id = this.question.id;

                axios.delete(`/forms/${this.formId}/questions/${id}`)
                    .then(response => {
                        this.loading = false;
                        flash('Question deleted');
                        this.$emit('questionDeleted', id);
                    }).catch(error => {
                    this.loading = false;
                    flash('Error deleting question. Please try again later', 'danger');
                });
            }

        }
    }
</script>

<style>
    .card-header {
        cursor: move;
    }

    .control-buttons {
        cursor: pointer;
    }
</style>