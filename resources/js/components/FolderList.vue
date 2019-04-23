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

        <b-modal v-model="showConfirmModal" title="Confirm deletion" ok-title="Delete folder" @ok="deleteFolder">
            Are you sure you want to delete this folder?
        </b-modal>

        <loading-modal v-if="loading"></loading-modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import {filter} from 'lodash';
    import LoadingModal from './Utils/LoadingModal';

    export default {
        components: {LoadingModal},

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

                        this.displayFolders = filter(this.displayFolders, folder => {
                            return folder.id !== this.folderToDelete;
                        });

                        this.folderToDelete = null;

                        flash('Folder deleted');
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