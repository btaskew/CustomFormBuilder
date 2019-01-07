<template>
    <div class="card" :class="{'mb-2 mt-2': isOpen}">
        <div class="card-header d-flex justify-content-between" :class="{'bg-primary text-white': isOpen}">
            {{ this.question.title }}
            <i class="fas fa-cog fa-lg" @click="toggleForm"></i>
        </div>

        <div v-if="isOpen" class="card-body">
            <question-form :question="question" :form-id="formId"></question-form>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['question', 'isOpen', 'formId'],

        methods: {
            toggleForm() {
                this.$emit('toggled', this.$vnode.key);
            }
        }
    }
</script>