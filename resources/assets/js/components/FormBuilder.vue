<template>
    <div v-if="isSubmitted" class="alert alert-success animated mt" role="alert">
        Form submitted
    </div>

    <div v-else-if="error" class="alert danger animated mt" role="alert">
        Error submitting form. Please try again later
    </div>

    <vue-form-generator v-else :schema="schema" :model="model" id="custom-form"></vue-form-generator>
</template>

<script>
    import axios from 'axios';
    import FormBuilder from './../classes/FormBuilder';

    export default {
        props: ["form", "questions", "isPreview"],

        data() {
            return {
                model: {},

                schema: {
                    fields: []
                },

                isSubmitted: false,
                error: false,
            }
        },

        created() {
            this.model = FormBuilder.buildModel(this.questions);
            this.schema.fields = FormBuilder.buildFields(this.questions);
            this.schema.fields.push({type: "submit", onSubmit: this.submitForm});
        },

        methods: {
            submitForm() {
                if (this.isPreview) {
                    return (this.isSubmitted = true);
                }
                
                const formData = new FormData();

                for (const field in this.model) {
                    formData.append(field, this.model[field]);
                }

                axios.post(`/forms/${this.form.id}/responses`, formData)
                    .then(response => {
                        this.isSubmitted = true;
                    }).catch(error => {
                        this.error = true;
                });
            }
        }
    }
</script>

<style>
    #custom-form label {
        display: flex;
    }

    #custom-form span.help {
        display: flex;
        align-items: center;
    }
</style>