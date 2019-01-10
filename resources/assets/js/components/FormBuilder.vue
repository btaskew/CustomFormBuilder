<template>
    <vue-form-generator :schema="schema" :model="model"></vue-form-generator>
</template>

<script>
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
        }
    }
</script>