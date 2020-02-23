<template>
    <div class="mt-4 mb-5">
        <h4>Add new user</h4>

        <cfb-form :form="form" @submitted="onSubmit">
            <input-field tag="username" label="Username" :value.sync="form.username" required></input-field>

            <select-field
                tag="access"
                label="Access type"
                :value.sync="form.access"
                :options="[{value: 'view', text: 'View'}, {value: 'update', text: 'Update'}]"
                required
            ></select-field>

            <button
                type="submit"
                class="btn btn-raised btn-primary"
                :disabled="loading"
                v-text="loading ? 'Loading' : 'Add user'"
            ></button>
        </cfb-form>
    </div>
</template>

<script>
    import Form from './../classes/Form';
    import LoadingModal from './Utils/LoadingModal';
    import CfbForm from './Utils/CfbForm';
    import InputField from './Utils/Fields/InputField';
    import SelectField from './Utils/Fields/SelectField';

    export default {
        props: ['formId'],

        components: {CfbForm, SelectField, InputField, LoadingModal},

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
                this.loading = true;

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
