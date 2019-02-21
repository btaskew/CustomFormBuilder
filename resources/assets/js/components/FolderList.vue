<template>
    <div>
        <div v-if="folders.length < 1" class="alert alert-info mt" role="alert">
            No folders found
        </div>

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col" class="p-2 align-text-top">Name</th>
                <th scope="col" class="p-2 align-text-top">Number of forms</th>
                <th scope="col" class="p-2 align-text-top"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="folder in this.folders">
                <td>{{ folder.name }}</td>
                <td>{{ folder.formCount }}</td>
                <td><i class="fas fa-trash-alt text-danger"></i></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['folders']
    }
</script>