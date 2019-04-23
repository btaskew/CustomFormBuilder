<template>
    <div>
        <div v-if="folders.length < 1" class="alert alert-info mt" role="alert">
            No folders found
        </div>

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col" class="p-2 align-text-top" style="width:40%">Name</th>
                <th scope="col" class="p-2 align-text-top">Number of forms</th>
                <th scope="col" class="p-2 align-text-top"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="folder in this.displayFolders">
                <td>{{ folder.name }}</td>
                <td>{{ folder.formCount }}</td>
                <td v-if="folder.formCount == 0">
                    <a href="#" @click="showModal(folder.id)">Delete</a>
                </td>
                <td v-else>
                    <a href="#" data-toggle="tooltip" title="Folders with forms in cannot be deleted"></a>
                </td>
            </tr>
            </tbody>
        </table>

        <modal v-if="showConfirmModal">
            <h4 slot="header">Confirm deletion</h4>

            <div slot="body">
                <p>Are you sure you want to delete this folder?</p>

                <div class="form-group flex-column">
                    <button type="button" @click="deleteFolder" class="btn btn-raised btn-primary">
                        Delete folder
                    </button>
                    <button type="button" @click="showConfirmModal = false" class="btn btn-raised btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </modal>

        <loading-modal v-if="loading"></loading-modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import {filter} from 'lodash';
    import LoadingModal from './Utils/LoadingModal';
    import Modal from './Utils/modal';

    export default {
        components: {LoadingModal, Modal},

        props: ['folders'],

        data() {
            return {
                displayFolders: this.folders,
                showConfirmModal: false,
                loading: false,
                folderToDelete: null
            }
        },

        methods: {
            showModal(id) {
                this.folderToDelete = id;
                this.showConfirmModal = true;
            },

            deleteFolder() {
                this.showConfirmModal = false;
                this.loading = true;

                axios.delete(`/admin/folders/${this.folderToDelete}`)
                    .then(response => {
                        this.loading = false;
                        flash('Folder deleted');

                        this.displayFolders = filter(this.displayFolders, folder => {
                            return folder.id !== this.folderToDelete;
                        });
                        this.folderToDelete = null;
                    }).catch(error => {
                    this.loading = false;
                    this.folderToDelete = null;
                    flash('Error deleting folder. Please try again later', 'danger');
                });
            }
        }
    }
</script>

<style>
    .folder-delete-icon {
        cursor: pointer;
    }
</style>