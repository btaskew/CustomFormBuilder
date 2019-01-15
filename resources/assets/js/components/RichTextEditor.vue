<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor input="trix" @trix-change="updateValue"></trix-editor>
    </div>
</template>

<script>
    import Trix from "trix";
    export default {
        props: ['value', 'name'],

        methods: {
            updateValue(e) {
                console.log("updating");
                this.$emit('update:value', e.target.value);
            },
        }
    }
</script>