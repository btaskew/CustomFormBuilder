<template>
    <div>
        <input :id="inputId" type="hidden" :name="name" :value="value">
        <trix-editor
            :input="inputId"
            @trix-change="updateValue"
            class="bg-white"
            :class="'trix-editor-' + this.name"
        ></trix-editor>
    </div>
</template>

<script>
    import Trix from "trix";

    export default {
        props: ['value', 'name'],

        computed: {
            inputId() {
                return this.name + '-trix';
            }
        },

        methods: {
            updateValue(e) {
                this.$emit('update:value', e.target.value);
            },
        }
    }
</script>

<style>
    trix-editor {
        min-height: 10em;
    }
</style>
