<template>
    <div class="card" :class="{'mb-2 mt-2 border-secondary': isOpen}">
        <div class="card-header d-flex justify-content-between" :class="{'bg-secondary text-white': isOpen}">
            {{ title }}
            <i class="fas fa-cog fa-lg" @click="toggleForm"></i>
        </div>

        <div v-if="isOpen" class="card-body">
            <question-form :question="question" :form-id="formId" @questionUpdated="this.updateTitle"></question-form>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['question', 'isOpen', 'formId'],

        data() {
            return {
                title: this.question.title
            }
        },

        methods: {
            toggleForm() {
                this.$emit('toggled', this.$vnode.key);
            },

            updateTitle(title) {
                this.title = title;
            }
        }
    }
</script>