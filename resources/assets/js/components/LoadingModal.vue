<template>
    <modal v-if="show">
        <h4 slot="header">Loading...</h4>

        <div slot="body">
            <div class="loader"></div>
        </div>
    </modal>
</template>

<script>
    import Modal from './modal';

    export default {
        components: {Modal},

        props: ['show']
    }
</script>