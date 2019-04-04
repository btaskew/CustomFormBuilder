<template>
    <dd-form :form="form" @submitted="onSubmit">
        <dd-form-input tag="name" label="Folder name" :value.sync="form.name">
            <template v-slot:append>
                <button type="submit"
                        class="btn btn-raised btn-primary"
                        :disabled="loading"
                        v-text="loading ? 'Loading' : 'Add folder'"
                ></button>
            </template>
        </dd-form-input>
    </dd-form>
</template>

<script>
    import {Form} from 'dd-js-package-components';

    export default {
        data() {
            return {
                form: new Form({
                    name: null,
                }),
                loading: false
            }
        },

        methods: {
            onSubmit() {
                this.form.post(`/admin/folders`)
                    .then(response => {
                        flash('Folder created');
                        this.loading = false;
                        this.form.name = null;
                    }).catch(error => {
                    this.loading = false;
                    flash('Error creating folder. Please try again later', 'danger');
                });
            }
        }
    }
</script>