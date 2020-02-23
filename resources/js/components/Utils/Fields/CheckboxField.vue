<template>
    <form-group
        :tag="tag"
        :description="description"
        label=""
        :error-message="this.errorMessage ? this.errorMessage : this.errors.get(this.tag)"
        :error-state="errors.state(tag)"
    >
        <b-form-checkbox
            :id="tag"
            :name="tag"
            :value="true"
            :checked="checked"
            :required="required"
            :disabled="disabled"
            :state="errors.state(tag)"
            :aria-invalid="errors.state(tag)"
            @change="handleInput"
        >
            {{ label }}
        </b-form-checkbox>
    </form-group>
</template>

<script>
    import FormGroup from './FormGroup';
    import Errors from './../../../classes/Errors';

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
            checked: {
                type: Boolean,
                required: true
            },
            description: {
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
                this.$emit('update:checked', value);
            }
        }
    }
</script>
