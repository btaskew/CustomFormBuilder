<template>
    <form-group
        :tag="tag"
        :description="description"
        :label="label"
        :error-message="this.errorMessage ? this.errorMessage : this.errors.get(this.tag)"
        :error-state="errors.state(tag)"
    >
        <b-form-textarea
            :id="tag"
            :name="tag"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            :rows="rows"
            :no-resize="noResize"
            :value="value"
            :state="errors.state(tag)"
            :aria-invalid="errors.state(tag)"
            @input="handleInput"
        >
        </b-form-textarea>
    </form-group>
</template>

<script>
    import Errors from './../../../classes/Errors';
    import FormGroup from './FormGroup';

    export default {
        components: {FormGroup},

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
                type: String
            },
            placeholder: {
                type: String
            },
            disabled: {
                type: Boolean,
                default: false
            },
            required: {
                type: Boolean,
                default: false
            },
            rows: {
                type: String,
                default: '3'
            },
            noResize: {
                type: Boolean,
                default: false
            },
            value: {
                type: String
            },
            errorMessage: {
                type: String
            }
        },

        inject: {
            errors: {
                type: Errors,
                required: true
            }
        },

        methods: {
            handleInput(value) {
                this.errors.clear(this.tag);
                this.$emit('update:value', value);
            }
        }
    }
</script>
