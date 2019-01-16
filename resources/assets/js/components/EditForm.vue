<template>
    <div>
        <form method="GET"
              @submit.prevent="onSubmit"
              @change="form.errors.clear($event)"
              @keydown="form.errors.clear($event)"
        >
            <div class="mt-t">
                <div class="form-group">
                    <label for="title" class="col-2 col-form-label" :class="{ 'has-error': form.errors.has('title') }">
                        Title
                    </label>
                    <input class="form-control" type="text" v-model="form.title" id="title" name="title" required>
                    <span class="text-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span>
                </div>

                <div class="form-group">
                    <label for="description"
                           class="col-2 col-form-label"
                           :class="{ 'has-error': form.errors.has('description') }"
                    >
                        Description
                    </label>
                    <rich-text-editor
                            id="description"
                            name="description"
                            :value.sync="form.description"
                    >
                    </rich-text-editor>
                    <span class="text-danger"
                          v-if="form.errors.has('description')"
                          v-text="form.errors.get('description')"
                    ></span>
                </div>

                <div class="form-row">
                    <div class="col form-group">
                        <label for="open_date"
                               class="col-form-label"
                               :class="{ 'has-error': form.errors.has('open_date') }"
                        >
                            Open date
                        </label>
                        <input class="form-control" type="date" v-model="form.open_date" id="open_date" name="open_date" required>
                        <span class="text-danger"
                              v-if="form.errors.has('open_date')"
                              v-text="form.errors.get('open_date')"
                        ></span>
                    </div>
                    <div class="col form-group">
                        <label for="close_date"
                               class="col-form-label"
                               :class="{ 'has-error': form.errors.has('close_date') }"
                        >
                            Closing date
                        </label>
                        <input class="form-control" type="date" v-model="form.close_date" id="close_date" name="close_date" required>
                        <span class="text-danger"
                              v-if="form.errors.has('close_date')"
                              v-text="form.errors.get('close_date')"
                        ></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="admin_email"
                           class="form-check-label"
                           :class="{ 'has-error': form.errors.has('admin_email') }"
                    >
                        Admin emails
                    </label>
                    <input class="form-control" type="text" v-model="form.admin_email" id="admin_email" name="admin_email" required>
                    <span class="text-danger"
                          v-if="form.errors.has('admin_email')"
                          v-text="form.errors.get('admin_email')"
                    ></span>
                </div>

                <div class="form-check form-group">
                    <input type="checkbox"
                           id="active"
                           name="active"
                           class="form-check-input"
                           v-model="form.active"
                           :true-value="true"
                           :false-value="false"
                    >
                    <label for="active"
                           class="form-check-label"
                           :class="{ 'has-error': form.errors.has('active') }"
                    >
                        Active
                    </label>
                    <span class="text-danger"
                          v-if="form.errors.has('active')"
                          v-text="form.errors.get('active')"
                    ></span>
                </div>
            </div>
            <button type="submit" class="btn btn-raised btn-primary mt" :disabled="loading">Save form</button>
        </form>

        <div class="loader" v-if="loading"></div>
    </div>
</template>

<script>
    import Form from '../classes/Form';

    export default {
        props: {formData: {default: null}},

        data() {
            return {
                form: new Form({
                    title: null,
                    description: null,
                    open_date: null,
                    close_date: null,
                    active: null,
                    admin_email: null,
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
                    flash("Error saving form. Please try again later", "danger");
                });
            },

            submitUpdate() {
                this.form.patch(`/forms/${this.formData.id}`)
                    .then(response => {
                        this.loading = false;
                        flash("Form updated");
                    }).catch(error => {
                    this.loading = false;
                    flash("Error updating form. Please try again later", "danger");
                });
            }
        }
    }
</script>

<style scoped>

</style>