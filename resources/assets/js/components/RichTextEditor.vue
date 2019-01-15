<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor input="trix" @trix-change="updateValue" class="bg-white"></trix-editor>
    </div>
</template>

<script>
    import Trix from "trix";
    export default {
        props: ['value', 'name'],

        methods: {
            updateValue(e) {
                this.$emit('update:value', e.target.value);
            },
        }
    }
</script>