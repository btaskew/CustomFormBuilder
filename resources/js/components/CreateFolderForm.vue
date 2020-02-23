<template>
    <cfb-form :form="form" @submitted="onSubmit">
        <input-field tag="name" label="Folder name" :value.sync="form.name">
            <template v-slot:append>
                <button
                    type="submit"
                    class="btn btn-raised btn-primary"
                    :disabled="loading"
                    v-text="loading ? 'Loading' : 'Add folder'"
                ></button>
            </template>
        </input-field>
    </cfb-form>
</template>

<script>
    import Form from './../classes/Form';
    import CfbForm from './Utils/CfbForm';
    import InputField from './Utils/Fields/InputField';

    export default {
        components: {CfbForm, InputField},
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
                this.loading = true;

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
