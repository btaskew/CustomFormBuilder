<template>
    <form-group
        :tag="tag"
        :description="description"
        :label="label"
        :error-message="this.errorMessage ? this.errorMessage : this.errors.get(this.tag)"
        :error-state="errors.state(tag)"
    >
        <b-input-group>
            <slot name="prepend" slot="prepend"></slot>

            <b-form-input
                :id="tag"
                :name="tag"
                :type="type"
                :placeholder="placeholder"
                :disabled="disabled"
                :required="required"
                :value="value"
                :state="errors.state(tag)"
                :aria-invalid="errors.state(tag)"
                @input="handleInput"
            >
            </b-form-input>

            <slot name="append" slot="append"></slot>
        </b-input-group>
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
            type: {
                type: String,
                default: 'text'
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
