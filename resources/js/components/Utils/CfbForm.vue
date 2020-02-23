<template>
    <form @submit.prevent="handleSubmit">
        <slot></slot>
    </form>
</template>

<script>
    import Form from '../../classes/Form';

    export default {
        props: {
            form: {
                type: Form,
                required: true,
            },
        },

        provide() {
            return {
                errors: this.form.errors
            }
        },

        methods: {
            handleSubmit() {
                this.$emit('submitted');
            }
        }
    }
</script>
