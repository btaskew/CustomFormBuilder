<template>
    <div>
        <edit-question
                v-for="(question, index) in questions"
                :question="question"
                :key="index"
                :is-open="visibleQuestion == index"
                :form-id="formId"
                @toggled="onToggle"
        >
        </edit-question>
    </div>
</template>

<script>
    import EditQuestion from './EditQuestion';

    export default {
        props: {
            questions: { default: {} },
            formId: {}
        },

        components: {EditQuestion},

        data() {
            return {
                visibleQuestion: null
            };
        },

        methods: {
            onToggle(index) {
                if (this.visibleQuestion == index) {
                    return (this.visibleQuestion = null);
                }
                this.visibleQuestion = index;
            }
        }
    }
</script>

<style scoped>

</style>