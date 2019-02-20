<template>
    <div>
        <h3>Edit form access</h3>

        <add-form-user :form-id="this.formId" @userAdded="addUser"></add-form-user>

        <hr />

        <form-access-list :users="this.users" :form-id="this.formId" @userRemoved="removeUser"></form-access-list>
    </div>
</template>

<script>
    import {filter} from 'lodash';
    import FormAccessList from './FormAccessList';
    import AddFormUser from './AddFormUser';

    export default {
        components: {AddFormUser, FormAccessList},

        props: ['formUsers', 'formId'],

        data() {
            return {
                users: this.formUsers
            }
        },

        methods: {
            addUser(user) {
                this.users.push(user);
            },

            removeUser(id) {
                this.users = filter(this.users, user => {
                    return user.pivot.id !== id
                });
            }
        }
    }
</script>