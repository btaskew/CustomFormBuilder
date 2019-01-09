<template>
    <form method="GET" @submit.prevent class="border p-2 options-form">
        <div class="form-row">
            <div class="col">
                <label for="value" class="col-form-label" :class="{ 'has-error': hasValueError }">
                    Value
                </label>
                <input class="form-control" type="text" :value="value" @change="updateValue" id="value" name="value" required>
                <span class="text-danger" v-if="hasValueError">Value field required</span>
            </div>
            <div class="col">
                <label for="display_value" class="col-form-label" :class="{ 'has-error': hasDisplayValueError }">
                    Display value
                </label>
                <input class="form-control" type="text" :value="displayValue" @change="updateDisplayValue" id="display_value" name="display_value" required>
                <span class="text-danger" v-if="hasDisplayValueError">Display value field required</span>
            </div>
            <div class="col col-1 ">
                <i class="fas fa-trash-alt text-danger col-form-label mt-1" @click="deleteOption"></i>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['id', 'value', 'displayValue', 'hasValueError', 'hasDisplayValueError'],

        methods: {
            updateValue(e) {
                this.$emit('update:value', e.target.value);
            },

            updateDisplayValue(e) {
                this.$emit('update:displayValue', e.target.value);
            },

            deleteOption() {
                this.$emit('deleteOption', this.id);
            }
        }
    }
</script>

<style>
    .options-form {
        padding-top: 0 !important;
    }
</style>