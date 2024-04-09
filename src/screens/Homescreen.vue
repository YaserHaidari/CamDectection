<template>
    <NavBarComp/>
    <div class="col-12" id="headerContainer">
        <h1>Approved mobile phone & seatbelt detection camera locations in Victoria</h1>
        <small>Published & updated by <a href="https://www.vic.gov.au/approved-mobile-camera-locations">Vicroad</a></small>
    </div>
    <table class="col-12">
        <thead>
            <tr>
                <th>Road</th>
                <th>Suburb</th>
                <th>latitude</th>
                <th>Longitute</th>
                <th>More Info</th>
            </tr>
        </thead>
        <tbody  v-for="(item, index) in filteredData" :key="index">
            <tr>
                <td>{{item.Road}}</td>
                <td>{{item.Suburb}}</td>
                <td>{{item.lat}}</td>
                <td>{{item.long}}</td>
                <td>
                        <button class="btn btn-link" @click="doMoreInfo(index)">Show</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>


<style scoped>
 #headerContainer{
    display: flex;
    flex: 1;
    background: linear-gradient(to right, #a7cee4, #1f9fe8);
    justify-content: center;
    align-items: center;
    height: 30vh;
    flex-direction: column;
 }
h1 {
    font-size: 2.4rem;
}
small{
    font-size: 1rem;
}
h1, small{
    color: #0052C2;
    font-weight: bold;
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    width: 50%;
    text-align: center;
}
tr > th {
    background-color: #0052C2;
    color: white;
    font-size: 1.2rem;
    font-weight: 500;
    padding-left: 10px;
    text-transform: uppercase;
}

tbody > tr > * {
    padding: 10px;
    background-color: #f2f2f2;
    border: 1px solid #0052C2;
}
table {
    border-collapse: separate;
    border-spacing: 4px;
}
</style>
<script>

import NavBarComp from '../components/NavBarComp.vue'
import Data from '../assets/Data.js'
// import { getAuth, onAuthStateChanged } from 'firebase/auth'
export default {
    name: 'HomeScreen',
    components: {
        NavBarComp
    },
    data(){
        return{
            username: '',
            filteredData: []
        }
    },
    methods:{
        doMoreInfo(index){
            this.$router.push({ name: 'Info', params: { id: index } });
        }
    },
    created(){
        Data.filter((item) => {
            this.filteredData.push(item)
        })
    }
}
</script>