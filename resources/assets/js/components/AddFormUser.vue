<template>
    <div class="mt-4 mb-5">
        <h4>Add new user</h4>

        <form method="GET"
              @submit.prevent="onSubmit"
              @change="form.errors.clear($event)"
              @keydown="form.errors.clear($event)"
              class="mt-3"
        >
            <label for="username" :class="{ 'has-error': form.errors.has('username') }">
                Username
            </label>
            <div class="input-group" id="username-input">
                <input class="form-control"
                       type="text"
                       v-model="form.username"
                       id="username"
                       name="username"
                       required
                >
                <span class="text-danger" v-if="form.errors.has('username')" v-text="form.errors.get('username')">
            </span>
                <div class="input-group-append">
                    <button type="submit"
                            class="btn btn-raised btn-primary"
                            :disabled="loading"
                            v-text="loading ? 'Loading' : 'Add user'"
                    >
                    </button>
                </div>
            </div>

        </form>
    </div>
</template>

<script>
    import Form from '../classes/Form';

    export default {
        props: ['formId'],

        data() {
            return {
                form: new Form({
                    username: null
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

                    if (error.data.error === 'Given user was not found in the database') {
                        return flash('Given user was not found in the database', 'danger');
                    }

                    flash('Error adding user. Please try again later', 'danger');
                });
            }
        }
    }
</script>