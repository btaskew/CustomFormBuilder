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
                    <textarea class="form-control"
                              type="text"
                              v-model="form.description"
                              id="description"
                              name="description"
                              rows=5
                    ></textarea>
                    <span class="text-danger"
                          v-if="form.errors.has('description')"
                          v-text="form.errors.get('description')"
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
        props: ['formData'],

        data() {
            return {
                form: new Form({
                    title: this.formData.title,
                    description: this.formData.description,
                }),
                loading: false,
                success: true,
                error: false
            }
        },

        methods: {
            onSubmit() {
                this.loading = true;

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