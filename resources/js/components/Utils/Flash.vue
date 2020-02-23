<template>
    <transition name="fade">
        <div
            :class="classes"
            style="right: 25px; bottom: 25px;"
            role="alert"
            v-show="show"
            v-text="body"
        >
        </div>
    </transition>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },

        computed: {
            classes() {
                let defaults = ['fixed', 'p-3', 'border', 'rounded', 'text-white'];

                if (this.level === 'success') defaults.push('bg-info');
                if (this.level === 'danger') defaults.push('bg-danger');

                return defaults;
            }
        },

        created() {
            if (this.message) {
                this.flash();
            }

            window.events.$on(
                'flash', data => this.flash(data)
            );
        },

        methods: {
            flash(data) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                }

                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: all .3s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
