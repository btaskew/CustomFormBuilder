<template>
    <div>
        <h3>Edit form access</h3>

        <form-access-list :users="this.users" :form-id="this.formId" @userRemoved="removeUser"></form-access-list>
    </div>
</template>

<script>
    import {filter} from 'lodash';
    import FormAccessList from './FormAccessList';
    
    export default {
        components: {FormAccessList},

        props: ['formUsers', 'formId'],

        data() {
            return {
                users: this.formUsers
            }
        },

        methods: {
            removeUser(id) {
                this.users = filter(this.users, user => {
                    return user.pivot.id !== id
                });
            }
        }
    }
</script>