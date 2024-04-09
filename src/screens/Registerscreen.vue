<template>
<div class="login-page">
   <transition name="fade">
      <div v-if="!registerActive" class="wallpaper-login"></div>
   </transition>
   <div class="wallpaper-register"></div>
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
               </form>
            </div>

            <div v-else class="card register" v-bind:class="{ error: emptyFields }">
               <h1>Sign Up</h1>
               <form class="form-group">
                  <input v-model="nameReg" type="text" class="form-control" placeholder="Name" required>
                  <input v-model="emailReg" type="email" class="form-control" placeholder="Email" required>
                  <input v-model="passwordReg" type="password" class="form-control" placeholder="Password" required>
                  <input v-model="confirmReg" type="password" class="form-control" placeholder="Confirm Password" required>
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
</template>

<script>
import { createUserWithEmailAndPassword, getAuth, signInWithEmailAndPassword, updateProfile } from 'firebase/auth'

export default {
   
   name: 'HelloWorld',
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
         const auth = getAuth()
         createUserWithEmailAndPassword(auth, this.emailReg, this.passwordReg).then((userCredential) => {
            // Signed in 
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
         const auth = getAuth();
         signInWithEmailAndPassword(auth, this.emailLogin, this.passwordLogin).then(() => {
            // const user = userCredential.user;
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
            }
         });

      }
   },
   created(){
      if(localStorage.getItem('currentUser')){
         this.isLoggedIn = true
      }
   }
}

</script>


<style scoped>
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

   .wallpaper-login {
      background: url(https://images.pexels.com/photos/32237/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260)
         no-repeat center center;
      background-size: cover;
      height: 100%;
      position: absolute;
      width: 100%;
   }
   
   .fade-enter-active,
   .fade-leave-active {
  transition: opacity .5s;
}
   .fade-enter,
   .fade-leave-to {
      opacity: 0;
   }
   
   .wallpaper-register {
      background: url(https://images.pexels.com/photos/533671/pexels-photo-533671.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260)
         no-repeat center center;
      background-size: cover;
      height: 100%;
      position: absolute;
      width: 100%;
      z-index: -1;
   }

   h1 {
      margin-bottom: 1.5rem;
   }
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