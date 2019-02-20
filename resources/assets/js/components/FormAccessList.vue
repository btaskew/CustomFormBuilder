<template>
    <div class="mt-2">
        <h4>Existing users with access</h4>

        <div v-if="this.users.length < 1" class="alert alert-info mt" role="alert">
            No additional users with access
        </div>

        <table v-else class="table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Access granted</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in this.users">
                <td>{{ user.name }}</td>
                <td>{{ user.username }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.pivot.created_at }}</td>
                <td><i class="fas fa-trash-alt text-danger" @click="showModal(user.pivot.id)"></i></td>
            </tr>
            </tbody>
        </table>

        <modal v-if="showConfirmModal">
            <h4 slot="header">Confirm deletion</h4>

            <div slot="body">
                <p>Are you sure you want to remove this users access?</p>

                <div class="form-group flex-column">
                    <button type="button" @click="deleteUser" class="btn btn-raised btn-primary">
                        Remove access
                    </button>
                    <button type="button" @click="hideModal" class="btn btn-raised btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </modal>

        <modal v-if="loading" :show="loading">
            <h4 slot="header">Loading...</h4>

            <div slot="body">
                <div class="loader"></div>
            </div>
        </modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import Modal from './modal';

    export default {
        components: {Modal},

        props: ['users', 'formId'],

        data() {
            return {
                showConfirmModal: false,
                userToDelete: null,
                loading: false
            }
        },

        methods: {
            showModal(user) {
                this.userToDelete = user;
                this.showConfirmModal = true;
            },

            hideModal() {
                this.userToDelete = null;
                this.showConfirmModal = false;
            },

            deleteUser() {
                this.showConfirmModal = false;
                this.loading = true;

                axios.delete(`/forms/${this.formId}/access/${this.userToDelete}`)
                    .then(response => {
                        this.loading = false;
                        this.userToDelete = null;
                        flash('User\'s access removed');
                    }).catch(error => {
                    this.loading = false;
                    this.userToDelete = null;
                    flash('Error removing user\'s access. Please try again later', 'danger');
                });
            }
        }
    }
</script>