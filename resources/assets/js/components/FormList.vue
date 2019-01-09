<template>
    <div class="mt-3">
        <div v-if="displayForms.length < 1" class="alert alert-info mt" role="alert">
            No forms found
        </div>

        <div v-else>
            <ul class="list-group">
                <li v-for="form in displayForms" class="list-group-item d-flex justify-content-between">
                    <span>{{ form.title }}</span>
                    <div>
                        <a href="#" class="mr-3" @click="confirmDelete(form.id)">Delete</a>
                        <a :href="editUrl(form.id)">Edit</a>
                    </div>
                </li>
            </ul>
        </div>

        <modal :show="showConfirmModal">
            <h4 slot="header">Confirm deletion</h4>

            <div slot="body">
                <p>Are you sure you want to delete this form? This action cannot be undone.</p>

                <div class="form-group flex-column">
                    <button type="button" @click="deleteForm" class="btn btn-raised btn-primary">
                        Delete form
                    </button>
                    <button type="button" @click="showConfirmModal = false" class="btn btn-raised btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </modal>

        <modal :show="loading">
            <h4 slot="header">Loading...</h4>

            <div slot="body">
                <div class="loader"></div>
            </div>
        </modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import {filter} from 'lodash';
    import Modal from './modal';

    export default {
        components: {Modal},

        props: {
            forms: { default: [] }
        },

        data() {
            return {
                displayForms: this.forms,
                formToDelete: null,
                showConfirmModal: false,
                loading: false
            };
        },

        methods: {
            editUrl(id) {
                return `/forms/${id}/edit`;
            },

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
                        this.removeForm();
                    }).catch(error => {
                    this.loading = false;
                    flash('Error deleting form. Please try again later', 'danger');
                });
            },

            removeForm(removeForm) {
                this.displayForms = filter(this.displayForms, form => {
                    return form.id !== this.formToDelete;
                });
                this.formToDelete = null;
            }
        }
    }
</script>