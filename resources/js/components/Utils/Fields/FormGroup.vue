<template>
    <b-form-group
        :id="tag + '-group'"
        :description="description"
        :label="label"
        :label-for="tag"
        :state="errorState"
        :invalid-feedback="errorMessage"
    >
        <slot></slot>
    </b-form-group>
</template>

<script>
    export default {
        props: {
            tag: {
                type: String,
                required: true,
            },
            label: {
                type: String,
                required: true,
            },
            description: {
                type: String,
            },
            errorMessage: {
                type: String
            },
            errorState: {
                type: Boolean,
                default: null
            }
        },
    }
</script>
