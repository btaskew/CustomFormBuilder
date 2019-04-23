<template>
    <div>
        <dd-form :form="form" @submitted="onSubmit">
            <dd-form-input tag="title" label="Title" :value.sync="form.title" required></dd-form-input>

            <dd-form-group
                    tag="description"
                    label="Description"
                    :error-message="form.errors.get('description')"
                    :error-state="form.errors.state('description')"
            >
                <rich-text-editor id="description" name="description" :value.sync="form.description">
                </rich-text-editor>
            </dd-form-group>

            <dd-select-input
                    tag="folder_id"
                    label="Folder"
                    :value.sync="form.folder_id"
                    :options="folders"
                    option-value-field="id"
                    option-text-field="name"
                    required
            ></dd-select-input>

            <div class="form-row">
                <div class="col">
                    <dd-form-input tag="open_date" label="Open date" :value.sync="form.open_date" type="date">
                    </dd-form-input>
                </div>

                <div class="col">
                    <dd-form-input tag="close_date" label="Closing date" :value.sync="form.close_date" type="date">
                    </dd-form-input>
                </div>
            </div>

            <dd-form-input
                    tag="admin_email"
                    label="Admin emails"
                    description="List of valid emails separated by semi-colons with no spaces"
                    :value.sync="form.admin_email"
            ></dd-form-input>

            <dd-form-input tag="success_text" label="Success text" :value.sync="form.success_text"></dd-form-input>

            <dd-select-input
                    tag="response_email_field"
                    label="Response email field"
                    :value.sync="form.response_email_field"
                    :options="this.emailQuestions"
                    option-value-field="id"
                    option-text-field="title"
                    :show-blank-option="true"
            ></dd-select-input>

            <dd-form-group
                    v-if="form.response_email_field"
                    tag="response_email"
                    label="Response email text"
                    :error-message="form.errors.get('response_email')"
                    :error-state="form.errors.state('response_email')"
            >
                <rich-text-editor id="response_email" name="response_email" :value.sync="form.response_email">
                </rich-text-editor>
            </dd-form-group>

            <dd-checkbox-input tag="active" label="Active" :checked.sync="form.active"></dd-checkbox-input>

            <button type="submit" class="btn btn-raised btn-primary mt" :disabled="loading">Save form</button>
        </dd-form>

        <div class="loader" v-if="loading"></div>
    </div>
</template>

<script>
    import {Form} from 'dd-js-package-components';

    export default {
        props: {
            formData: {default: null},
            folders: {default: null},
            emailQuestions: {default: []}
        },

        data() {
            return {
                form: new Form({
                    title: null,
                    description: null,
                    open_date: null,
                    close_date: null,
                    active: false,
                    admin_email: null,
                    success_text: null,
                    folder_id: null,
                    response_email_field: null,
                    response_email: null,
                }),
                loading: false,
                success: true,
                error: false,
                isNewForm: true
            }
        },

        created() {
            if (this.formData) {
                this.form.title = this.formData.title;
                this.form.description = this.formData.description;
                this.form.open_date = this.formData.open_date;
                this.form.close_date = this.formData.close_date;
                this.form.active = this.formData.active;
                this.form.admin_email = this.formData.admin_email;
                this.form.success_text = this.formData.success_text;
                this.form.folder_id = this.formData.folder_id;
                this.form.response_email_field = this.formData.response_email_field;
                this.form.response_email = this.formData.response_email;
                this.isNewForm = false;
            }
        },

        methods: {
            onSubmit() {
                this.loading = true;

                if (this.isNewForm) {
                    return (this.submitNewForm());
                }

                this.submitUpdate();
            },

            submitNewForm() {
                this.form.post(`/forms`)
                    .then(response => {
                        this.loading = false;
                        window.location = `/forms/${response.id}/edit`;
                    }).catch(error => {
                    this.loading = false;
                    flash('Error saving form. Please try again later', 'danger');
                });
            },

            submitUpdate() {
                this.form.patch(`/forms/${this.formData.id}`)
                    .then(response => {
                        this.loading = false;
                        flash('Form updated');
                    }).catch(error => {
                    this.loading = false;
                    flash('Error updating form. Please try again later', 'danger');
                });
            }
        }
    }
</script>