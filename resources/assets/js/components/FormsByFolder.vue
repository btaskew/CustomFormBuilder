<template>
    <div class="mt-3">
        <span class="mr-2">Folder:</span>
        <div class="btn-group" role="group" aria-label="Folders">
            <button v-for="(forms, folder) in this.folders"
                    type="button"
                    class="btn"
                    :class="activeButtonClass(folder)"
                    @click="changeFolder(folder)"
            >
                {{ folder }}
            </button>
        </div>

        <form-list :forms="this.displayForms"></form-list>
    </div>
</template>

<script>
    export default {
        props: {
            folders: {default: []}
        },

        data() {
            return {
                selectedFolder: Object.keys(this.folders)[0]
            }
        },

        computed: {
            displayForms() {
                return this.folders[this.selectedFolder];
            },
        },

        methods: {
            changeFolder(folder) {
                this.selectedFolder = folder;
            },

            activeButtonClass(folder) {
                return folder === this.selectedFolder ? 'btn-primary' : 'btn-light';
            }
        }
    }
</script>