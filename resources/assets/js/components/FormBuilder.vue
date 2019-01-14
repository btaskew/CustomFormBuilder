<template>
    <vue-form-generator :schema="schema" :model="model"></vue-form-generator>
</template>

<script>
    import axios from 'axios';
    import FormBuilder from './../classes/FormBuilder';

    export default {
        props: ["form", "questions"],

        data() {
            return {
                model: {},

                schema: {
                    fields: []
                }
            }
        },

        created() {
            this.model = FormBuilder.buildModel(this.questions);
            this.schema.fields = FormBuilder.buildFields(this.questions);
            this.schema.fields.push({type: "submit", onSubmit: this.submitForm});
        },

        methods: {
            submitForm() {
                const formData = new FormData();

                for (const field in this.model) {
                    formData.append(field, this.model[field]);
                }

                axios.post(`/forms/${this.form.id}/responses`, formData)
                    .then(response => {
                        // alert("submitted");
                    }).catch(error => {
                    // alert("error");
                });
            }
        }
    }
</script>