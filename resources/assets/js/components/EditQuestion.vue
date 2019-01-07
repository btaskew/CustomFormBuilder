<template>
    <div class="card" :class="{'mb-3': showForm}">
        <div class="card-header d-flex justify-content-between">
            {{ this.question.title }}
            <i class="fas fa-cog fa-lg" @click="toggleForm"></i>
        </div>

        <transition name="slide">
            <div v-if="showForm" class="card-body">
                Edit form here
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: ['question'],

        data() {
            return {
                showForm: false
            }
        },

        methods: {
            toggleForm() {
                this.showForm = !this.showForm;
            }
        }
    }
</script>

<style scoped>
    .slide-enter-active, .slide-leave-active {
        transition: all .2s;
    }

    .slide-enter, .slide-leave-to {
        transform: translateY(-20px);
    }
</style>