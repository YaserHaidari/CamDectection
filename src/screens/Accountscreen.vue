<template>
<div style="margin: 10px;">
    <h1>Account Info</h1>
    <p>Name: {{username}}</p>
    <p>Emai: {{email}}</p>
    <p>Created Date: {{ accountCreatedDate }}</p>
    <router-link to="/Home">
        <button style="width: 50%;" class="btn btn-primary">Back</button>
    </router-link>
</div>

</template>

<script>
import { getAuth, onAuthStateChanged } from 'firebase/auth';
export default{
    name: 'AccountScreen',
    data(){
        return{
            username: '',
            email: '',
            accountCreatedDate: ''
        }
    },
    created(){
        onAuthStateChanged(getAuth(), user => {
            if(user){
                console.log(user.providerData)
                this.username = user.displayName
                this.email = user.email
                this.accountCreatedDate = user.metadata.creationTime
            }
        })
    }
}
</script>
<style>
</style>