<template>
<div class="login-page">
   <transition name="fade">
      <div v-if="!registerActive" class="wallpaper-login"></div>
   </transition>
   <div class="container">
      <div class="row">
         <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
            <div v-if="!registerActive" class="card login" v-bind:class="{ error: emptyFields }">
               <h1>Sign In</h1>
               <form class="form-group">
                  <input v-model="emailLogin" type="email" class="form-control" placeholder="Email" required>
                  <input v-model="passwordLogin" type="password" class="form-control" placeholder="Password" required>
                  <input type="submit" class="btn btn-primary" @click.prevent="doLogin">
                  <p>Don't have an account? <a href="#" @click="registerActive = !registerActive, emptyFields = false">Sign up here</a>
                  </p>
                  <p><a href="#">Forgot your password?</a></p>
                  <p>{{errMsg}}</p>
               </form>
            </div>

            <div v-else class="card register" v-bind:class="{ error: emptyFields }">
               <h1>Sign Up</h1>
               <form class="form-group">
                  <input v-model.trim="nameReg" type="text" class="form-control" placeholder="Name" required>
                  <input v-model.trim="emailReg" type="email" class="form-control" placeholder="Email" required>
                  <input v-model.trim="passwordReg" type="password" class="form-control" placeholder="Password" required>
                  <input v-model.trim="confirmReg" type="password" class="form-control" placeholder="Confirm Password" required>
                  <input type="submit" class="btn btn-primary" @click.prevent="doRegister">
                  <p>Already have an account? <a href="#" @click="registerActive = !registerActive, emptyFields = false">Sign in here</a>
                  </p>
                  <p>{{errMsg}}</p>
               </form>
            </div>
         </div>
         
      </div>
   </div>
</div>
<FooterComp/>
</template>

<script>
import { createUserWithEmailAndPassword, getAuth, signInWithEmailAndPassword, updateProfile } from 'firebase/auth'
import FooterComp from '@/components/FooterComp.vue'
export default {
   //changed
   name: 'RegisterUser',
   components: {
      FooterComp
   },
   data(){
      return{
         nameReg: '',
         emailLogin: '',
         passwordLogin: '',
         emailReg: '',
         passwordReg: '',
         confirmReg: '',
         registerActive: true,
         emptyFields: false,
         errMsg: ''
      }
   },
   methods:{
      doRegister(){
         if(this.nameReg === '' || this.emailReg === '' || this.passwordReg === '' || this.confirmReg === ''){
            this.emptyFields = true
            this.errMsg = 'Please fill in all fields'
            return
         } else if (this.emailReg.indexOf('@') === -1 || this.emailReg.indexOf('.') === -1){
            this.emptyFields = true
            this.errMsg = 'Invalid email'
            return
         } else if(this.passwordReg !== this.confirmReg){
            this.emptyFields = true
            this.errMsg = 'Passwords do not match'
            return
         }
         const auth = getAuth()
         createUserWithEmailAndPassword(auth, this.emailReg, this.passwordReg).then((userCredential) => {
            const user = userCredential.user;
            this.errMsg = ''
            localStorage.setItem('isLoggedIn', true)
            this.$router.push('/home')
            return updateProfile(user, {
               displayName: this.nameReg
            })
         }).catch((error) => {
            this.errMsg = error.code
            if(error.code === 'auth/email-already-in-use'){
               this.errMsg = 'Email already in use'
            }else if (error.code === 'auth/invalid-email'){
               this.errMsg = 'Invalid email'
            } else if (error.code === 'auth/missing-password'){
               this.errMsg = 'Missing password'
            }
         });
      },
      doLogin(){
         if(this.emailLogin === '' || this.passwordLogin === ''){
            this.emptyFields = true
            this.errMsg = 'Please fill in all fields'
            return
         }
         if(this.emailLogin.indexOf('@') === -1 || this.emailLogin.indexOf('.') === -1){
            this.emptyFields = true
            this.errMsg = 'Invalid email'
            return
         }
         const auth = getAuth();
         signInWithEmailAndPassword(auth, this.emailLogin, this.passwordLogin).then(() => {
            // const user = userCredential.user;
            this.errMsg = ''
            this.$router.push("/home")
            localStorage.setItem('isLoggedIn', true)
         }).catch((error) => {
            this.errMsg = error.code
            if(error.code === 'auth/user-not-found'){
               this.errMsg = 'User not found'
            }else if (error.code === 'auth/wrong-password'){
               this.errMsg = 'Wrong password'
            } else if (error.code === 'auth/invalid-email'){
               this.errMsg = 'Invalid email'
            }else if (error.code === 'auth/invalid-credential'){
               this.errMsg = 'Wrong password or email. Please try again'
            }

           
         });

      }
   },
   created(){
      if(localStorage.getItem('isLoggedIn')){
         this.$router.push('/home')
      }
      // if(localStorage.getItem('isLoggedIn')){
      //    this.isLoggedIn = true
      // }
   }
}

</script>


<style scoped>
nav{
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    font-size: 1.1rem;
    font-weight: 500;
    background-color: #563D7C;
}
nav > *{
    padding: 20px;
}
nav li{
    padding-left: 15px;
}
p {
   line-height: 1rem;
}

.card {
   padding: 20px;
}

.form-group {
   input {
      margin-bottom: 20px;
   }
}

.login-page {
   align-items: center;
   display: flex;
   height: 100vh;
}
   .fade-enter-active,
   .fade-leave-active {
  transition: opacity .5s;
}
   .fade-enter,
   .fade-leave-to {
      opacity: 0;
   }

   h1 {
      margin-bottom: 1.5rem;
   }

.error {
   animation-name: errorShake;
   animation-duration: 0.3s;
}

@keyframes errorShake {
   0% {
      transform: translateX(-25px);
   }
   25% {
      transform: translateX(25px);
   }
   50% {
      transform: translateX(-25px);
   }
   75% {
      transform: translateX(25px);
   }
   100% {
      transform: translateX(0);
   }
}


</style>