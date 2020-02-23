<template>
    <form-group
        :tag="tag"
        :description="description"
        :label="label"
        :error-message="this.errorMessage ? this.errorMessage : this.errors.get(this.tag)"
        :error-state="errors.state(tag)"
    >
        <b-form-select
            :id="tag"
            :name="tag"
            :disabled="disabled"
            :required="required"
            :value="value"
            :state="errors.state(tag)"
            :aria-invalid="errors.state(tag)"
            @input="handleInput"
        >
            <option v-if="showFirstOption" :value="null" disabled :id="tag + '-first-option'">
                -- Please select an option --
            </option>
            <option v-if="showBlankOption" :value="null" :id="tag + '-blank-option'">
                None
            </option>
            <option v-for="option in options" :value="option[optionValueField]">
                {{ option[optionTextField] }}
            </option>
        </b-form-select>
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
            options: {
                type: Array,
                required: true
            },
            optionValueField: {
                type: String,
                default: 'value'
            },
            optionTextField: {
                type: String,
                default: 'text'
            },
            value: {
                required: true
            },
            description: {
                type: String
            },
            disabled: {
                type: Boolean,
                default: false
            },
            showFirstOption: {
                type: Boolean,
                default: false
            },
            showBlankOption: {
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
                this.$emit('update:value', value);
            }
        }
    }
</script>
