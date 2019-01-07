<template>
    <div class="card" :class="{'mb-2 mt-2': isOpen}">
        <div class="card-header d-flex justify-content-between">
            {{ this.question.title }}
            <i class="fas fa-cog fa-lg" @click="toggleForm"></i>
        </div>

        <div v-if="isOpen" class="card-body">
            Edit form here
        </div>
    </div>
</template>

<script>
    export default {
        props: ['question', 'isOpen'],

        data() {
            return {

            }
        },

        methods: {
            toggleForm() {
                this.$emit('toggled', this.$vnode.key);
            }
        }
    }
</script>