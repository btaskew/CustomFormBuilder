<template>
    <form method="GET" @submit.prevent class="border p-2 options-form">
        <div class="form-row">
        <div class="col">
            <label for="value" class="col-form-label" >
                Value
            </label>
            <input class="form-control" type="text" :value="value" @change="updateValue" id="value" name="value" required>
        </div>
        <div class="col">
            <label for="display_value" class="col-form-label">
                Display value
            </label>
            <input class="form-control" type="text" :value="displayValue" @change="updateDisplayValue" id="display_value" name="display_value" required>
        </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['value', 'displayValue'],

        methods: {
            updateValue(e) {
                this.$emit('update:value', e.target.value);
            },
            updateDisplayValue(e) {
                this.$emit('update:displayValue', e.target.value);
            }
        }
    }
</script>

<style>
    .options-form {
        padding-top: 0 !important;
    }
</style>