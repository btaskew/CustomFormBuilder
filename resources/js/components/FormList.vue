<template>
    <div class="mt-3">
        <div v-if="displayForms.length < 1" class="alert alert-info mt" role="alert">
            No forms found
        </div>

        <div v-else>
            <ul class="list-group">
                <li v-for="form in displayForms" class="list-group-item d-flex justify-content-between">
                    <span>{{ form.title }}</span>
                    <div v-if="form.editAccess">
                        <a href="#" class="mr-3" @click="confirmDelete(form.id)">Delete</a>
                        <a :href="`/forms/${form.id}/edit`">Edit</a>
                    </div>
                    <div v-else>
                        <a :href="`/forms/${form.id}/preview`">View</a>
                    </div>
                </li>
            </ul>
        </div>

        <b-modal v-model="showConfirmModal" title="Confirm deletion" ok-title="Delete form" @ok="deleteForm">
            Are you sure you want to delete this form? This action cannot be undone.
        </b-modal>

        <loading-modal v-if="loading"></loading-modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import LoadingModal from './Utils/LoadingModal';

    export default {
        components: {LoadingModal},

        props: {
            forms: {default: []}
        },

        data() {
            return {
                formToDelete: null,
                showConfirmModal: false,
                loading: false,
            };
        },

        computed: {
            displayForms() {
                return this.forms;
            }
        },

        methods: {
            confirmDelete(id) {
                this.formToDelete = id;
                this.showConfirmModal = true;
            },

            deleteForm() {
                this.showConfirmModal = false;
                this.loading = true;

                axios.delete(`/forms/${this.formToDelete}`)
                    .then(response => {
                        this.loading = false;
                        flash('Form deleted');
                        this.$emit('formDeleted', this.formToDelete);
                        this.formToDelete = null;
                    }).catch(error => {
                    this.loading = false;
                    flash('Error deleting form. Please try again later', 'danger');
                });
            }
        }
    }
</script>