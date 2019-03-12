<template>
    <form method="GET"
          @submit.prevent="onSubmit"
          @change="form.errors.clear($event)"
          @keydown="form.errors.clear($event)"
          class="mt-3"
    >
        <label for="name" :class="{ 'has-error': form.errors.has('name') }">
            Folder name
        </label>
        <div class="input-group">
            <input class="form-control"
                   type="text"
                   v-model="form.name"
                   id="name"
                   name="name"
                   required
            >
            <span class="text-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
            <div class="input-group-append">
                <button type="submit"
                        class="btn btn-raised btn-primary"
                        :disabled="loading"
                        v-text="loading ? 'Loading' : 'Add folder'"
                ></button>
            </div>
        </div>

    </form>
</template>

<script>
    import Form from '../classes/Form';

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