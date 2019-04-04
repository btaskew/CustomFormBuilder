<template>
    <div class="mt-4 mb-5">
        <h4>Add new user</h4>

        <dd-form :form="form" @submitted="onSubmit">
            <dd-form-input tag="username" label="Username" :value.sync="form.username" required></dd-form-input>

            <dd-select-input
                tag="access"
                label="Access type"
                :value.sync="form.access"
                :options="[{value: 'view', text: 'View'}, {value: 'update', text: 'Update'}]"
                required
            ></dd-select-input>

            <button type="submit"
                    class="btn btn-raised btn-primary"
                    :disabled="loading"
                    v-text="loading ? 'Loading' : 'Add user'"
            ></button>
        </dd-form>
    </div>
</template>

<script>
    import {Form} from 'dd-js-package-components';

    export default {
        props: ['formId'],

        data() {
            return {
                form: new Form({
                    username: null,
                    access: null
                }),
                loading: false
            }
        },

        methods: {
            onSubmit() {
                this.form.post(`/forms/${this.formId}/access`)
                    .then(response => {
                        flash('Access granted to user');
                        this.$emit('userAdded', response);
                        this.loading = false;
                        this.form.username = null;
                    }).catch(error => {
                    this.loading = false;

                    if (error.data.error) {
                        return flash(error.data.error, 'danger');
                    }

                    flash('Error adding user. Please try again later', 'danger');
                });
            }
        }
    }
</script>