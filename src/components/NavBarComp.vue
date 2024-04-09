<template>
   <div>
    <nav class="navbar navbar-expand-lg navbar-dark ">
  <a class="navbar-brand" href="#">{{username}}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <RouterLink to="/Home">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </RouterLink>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <router-link to="/Map">
            <a class="dropdown-item" href="#">Google Maps</a>
          </router-link>
          <a class="dropdown-item" href="#">List view</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Logout</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
   </div>
</template>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

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
</style>
<script>
import { getAuth, onAuthStateChanged } from 'firebase/auth'
export default {
    name: 'NavBarComp',
    data(){
        return{
            username: ''
        }
    },
    created(){
        onAuthStateChanged(getAuth(), user => {
            if(user){
                this.username = user.displayName
            }
        })
    }
    
}

</script>