<template>
    <div class="col-md-12">
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

        <div v-if="loading" class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
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
                this.form.patch(`/forms/${this.formData.id}`)
                    .then(response => {
                        this.loading = false;
                        this.success = true;
                    }).catch(error => {
                    this.buttonDisabled = false;
                    this.error = true;
                });
            }
        }
    }
</script>

<style scoped>

</style>