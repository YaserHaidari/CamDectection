<template>
    <div style="margin: 10px; display: flex; flex: 1%; justify-content: center;
    align-items: center; flex-direction: column;" class="col-12" >
        <img 
        style="width: 80wh; height: 60vh;
        "
        src="https://i.pinimg.com/originals/15/f6/2d/15f62d0bf06c29ce4bfccbb641605682.png"/>
        <h1>Under Construction</h1>
        <p>This page is under construction. Please check back later.</p>
        <router-link to="/Home">
            <button style="width: 100%;" class="btn btn-primary">Back</button>
        </router-link>
    </div>
    
    </template>
    
    <script>
    import { getAuth, onAuthStateChanged } from 'firebase/auth';
    export default{
        name: 'UnderConstructionscreen',
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