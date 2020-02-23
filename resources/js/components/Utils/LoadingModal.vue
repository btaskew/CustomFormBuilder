<template>
    <b-modal
        v-model="visible"
        title="Loading..."
        :hide-footer="true"
        :no-close-on-esc="true"
        :no-close-on-backdrop="true"
        :hide-header-close="true"
    >
        <div class="loader"></div>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                visible: true
            };
        }
    }
</script>
