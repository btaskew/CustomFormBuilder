<template>
    <div>
        <form method="GET"
              @submit.prevent="onSubmit"
              @change="form.errors.clear($event)"
              @keydown="form.errors.clear($event)"
        >
            <div class="mt-2 form-row">

                <div class="col">
                    <div class="form-group">
                        <label for="title" :class="{ 'has-error': form.errors.has('title') }">
                            Title
                        </label>
                        <input class="form-control" type="text" v-model="form.title" id="title" name="title" required>
                        <span class="text-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span>
                    </div>

                    <div class="form-group">
                        <label for="type" :class="{ 'has-error': form.errors.has('order') }">
                            Order
                        </label>
                        <input class="form-control" type="text" v-model="form.order" id="order" name="order">
                        <span class="text-danger" v-if="form.errors.has('order')" v-text="form.errors.get('order')"></span>
                    </div>

                    <div class="form-group">
                        <label for="help_text"
                               :class="{ 'has-error': form.errors.has('help_text') }"
                        >
                            Help text for question
                        </label>
                        <textarea class="form-control"
                                  type="text"
                                  v-model="form.help_text"
                                  id="help_text"
                                  name="help_text"
                                  rows=2
                        ></textarea>
                        <span class="text-danger"
                              v-if="form.errors.has('help_text')"
                              v-text="form.errors.get('help_text')"
                        ></span>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="type" :class="{ 'has-error': form.errors.has('type') }">
                            Question type
                        </label>
                        <input class="form-control" type="text" v-model="form.type" id="type" name="type" required>
                        <span class="text-danger" v-if="form.errors.has('type')" v-text="form.errors.get('type')"></span>
                    </div>

                    <div class="form-check form-group">
                        <input type="checkbox"

                               id="admin_only"
                               name="admin_only"
                               class="form-check-input"
                               v-model="form.admin_only"
                               :true-value="true"
                               :false-value="false"
                        >
                        <label for="type"
                               class="form-check-label"
                               :class="{ 'has-error': form.errors.has('admin_only') }"
                        >
                            Admin only field
                        </label>
                        <span class="text-danger"
                              v-if="form.errors.has('admin_only')"
                              v-text="form.errors.get('admin_only')"
                        ></span>
                    </div>
                    <div class="form-check form-group">
                        <input type="checkbox"
                               id="question_required"
                               name="question_required"
                               class="form-check-input"
                               v-model="form.question_required"
                               :true-value="true"
                               :false-value="false"
                        >
                        <label for="type"
                               class="form-check-label"
                               :class="{ 'has-error': form.errors.has('question_required') }"
                        >
                            Required
                        </label>
                        <span class="text-danger"
                              v-if="form.errors.has('question_required')"
                              v-text="form.errors.get('question_required')"
                        ></span>
                    </div>
                </div>


            </div>

            <button type="submit" class="btn btn-raised btn-primary mt" :disabled="loading">Save question</button>
        </form>

        <div class="loader" v-if="loading"></div>
    </div>
</template>

<script>
    import Form from '../classes/Form';

    export default {

        props: ['formId'],

        data() {
            return {
                form: new Form({
                    title: '',
                    type: '',
                    help_text: '',
                    question_required: false,
                    admin_only: false,
                    order: ''
                }),
                loading: false,
                success: true,
                error: false
            }
        },

        methods: {
            onSubmit() {
                this.loading = true;

                this.form.post(`/forms/${this.formId}/questions`)
                    .then(response => {
                        this.loading = false;
                        flash()
                        window.location = `/forms/${this.formId}/questions`;
                    }).catch(error => {
                    this.loading = false;
                    flash("Error updating form. Please try again later", "danger");
                });
            }
        }
    }
</script>