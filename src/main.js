import { createApp } from 'vue'
import App from './App.vue'
import { firestorePlugin } from 'vuefire'
import { initializeApp } from 'firebase/app'
import { getAuth } from 'firebase/auth'
import 'firebase/auth' // Import Firebase Authentication
import * as VueRouter from 'vue-router'
import Home from './screens/Homescreen.vue'
import Register from './screens/Registerscreen.vue'
import Info from './screens/Informationscreen.vue'
import Maps from './screens/Mapscreen.vue'

const router = VueRouter.createRouter({
    // history: VueRouter.createWebHistory(process.env.BASE_URL),
    history: VueRouter.createWebHistory("/cos20031/s104608220/dist/"),

    routes: [
        {
            path: '/',
            component: Register
        },
        {
            path: '/Home',
            component: Home
        },
        {
            path: '/Register',
            component: Register
        },
        {
            path: '/Info/:id',
            name: 'Info',
            component: Info
        },
        {
            path: '/Map/:id',
            name: 'Map',
            component: Maps
        }
    ]

})
// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDD25vvASFhLog8Prp3XrPadgBzVSOTg08",
    authDomain: "swinburne-2f794.firebaseapp.com",
    projectId: "swinburne-2f794",
    storageBucket: "swinburne-2f794.appspot.com",
    messagingSenderId: "270602398777",
    appId: "1:270602398777:web:b777dd476c3759fd8971e8",
    measurementId: "G-D7MC4GV44X"
}

const app = initializeApp(firebaseConfig)
const auth = getAuth(app)

createApp(App)
  .use(firestorePlugin)
  .provide('auth', auth)
  .use(router)
  .mount('#app')
