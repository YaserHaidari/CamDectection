<template>
    <NavBarComp @search="handleSearch" :Livecoordinates="`lat: ${center.lat}, lng: ${center.lng}`"/>
     <div class="col-12" id="headerContainer">
            <h1>Approved mobile phone & seatbelt detection camera locations in Victoria</h1>
            <small>Published & updated by <a href="https://www.vic.gov.au/approved-mobile-camera-locations">Vicroad</a></small>
            <button class="btn btn-warning" @click="reportCam(center.lat, center.lng)">Report Camera here</button>
        </div>
        <table class="col-12">
            <thead>
                <tr>
                    <th>Road</th>
                    <th>Suburb</th>
                    <th v-if="showLatLong">latitude</th>
                    <th v-if="showLatLong">Longitute</th>
                    <th>More Info</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody  v-for="(item, index) in filteredData" :key="index">
                <tr>
                    <td>{{item.Road}}</td>
                    <td>{{item.Suburb}}</td>
                    <td v-if="showLatLong">{{item.lat}}</td>
                    <td v-if="showLatLong">{{item.lng}}</td>
                    <td>Votes: {{item.Upvote}}</td>
                    <td id="tBtn" style="display: flex;flex-direction: row; flex-wrap: wrap;">
                        <button class="btn btn-link" @click="doMoreInfo(index)">Show</button>
                        <button class="btn btn-success" type="submit" @click="upVoteBtn(index)">
                            <i class="bi bi-hand-thumbs-up"></i>
                        </button>
                        <button class="btn btn-warning">
                            <i class="bi bi-hand-thumbs-down"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <FooterComp/>
    </template>


    <script>
    
    import NavBarComp from '../components/NavBarComp.vue'
    // import { getAuth, onAuthStateChanged } from 'firebase/auth'
    import axios from 'axios';
    import FooterComp from '@/components/FooterComp.vue';
    export default {
        name: 'HomeScreen',
        components: {
            NavBarComp,
            FooterComp
        },
        data(){
            return{
                username: '',
                filteredData: [],
                center: {},
                Data: [],
                err: '',
                msg: '',
                screenWidth: window.innerWidth,
                screenHeight: window.innerHeight,
                showLatLong: true,
                suburb: '',  // Add this line
                road: '',  // Add this line
                post_code: ''  // Add this line
            }
        },

        methods:{
            doMoreInfo(index){
                this.$router.push({ name: 'Map', params: { id: index } });
            },
            handleSearch(searchText){
                let Data = this.Data;
                searchText = String(searchText)
                if(searchText === '' || searchText === null){
                    this.filteredData = Data
                }
                const searchedData = Data.filter((item) => {
                    return item.Road.toLowerCase().includes(searchText.toLowerCase()) 
                    || item.Suburb.toLowerCase().includes(searchText.toLowerCase())
                })
                this.filteredData = searchedData
            },
            upVoteBtn(id){
                console.log(id+1);
                var putSQLApiURL = `/cos20031/s104608220/api/apis2.php/${this.filteredData[id].id}`;
                const requestOptions = {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "Upvote": 1 // Example: Increment Upvote by 1
                    })
                };

                fetch(putSQLApiURL, requestOptions).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // Parse response as JSON
                }).then(data => {
                    console.log(data + "AHAHA");
                    alert("Upvote incremented successfully");
                    location.reload();
                }).catch(error => console.error("Error:", error));
            },
            async reportCam(lat, lng){
                if(lat === undefined || lng === undefined){
                    alert("Please enable location services to report camera");
                    return;
                }
                try {
                    const response = 
                    await axios.get(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyCBOwKCfojuQAZcBOxmUulYBsfcRRtR9LU`);
                    const data = response.data;
                    if (data.status === 'OK') {
                        const components = data.results[0].address_components;
                        const suburb = components.find(component => component.types.includes('locality'));
                        const road = components.find(component => component.types.includes('route'));
                        const post_code = components.find(component => component.types.includes('postal_code'));
                        this.suburb = suburb ? suburb.long_name : null;
                        this.road = road ? road.long_name : null;
                        this.post_code = post_code ? post_code.long_name : null;
                    if (confirm(`You are reporting a camera at ${this.road}, ${this.suburb}, ${this.post_code}. Do you want to proceed?`)) {
                        const postData = {
                            id: this.Data.length + 1,
                            Road: this.road,
                            Suburb: this.suburb,
                            lat: lat,
                            lng: lng,
                            Upvote: 1,
                            DownVote: 0,
                            Status: 'Active',
                            Date: new Date().getMonth() + 1 + '/' + new Date().getDate(),
                            Day: new Date().getDay(),
                            Time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                        };
                        await axios.post('/cos20031/s104608220/api/apis.php', postData);
                        alert("Camera reported successfully");
                        location.reload();
                    } else {
                        console.log('Report cancelled')
                    }
                } else {
                    console.error(data.error_message);
                    throw new Error(`Geocoding error: ${data.status}`);
                }
                } catch (error) {
                    console.error(error);
                }
            },
        }, 
        mounted(){
           if(this.screenWidth < 1000){
               this.showLatLong = false;
           }
        var self = this;
        var readSQLApiURL = '/cos20031/s104608220/api/apis.php';
        fetch(readSQLApiURL)
        .then(response => response.json())
        .then(data => {
            self.Data = data;
            self.filteredData = [...data];
            console.log(self.filteredData)
            self.msg = "Successful!";
        }).catch(error => {
            self.err = error;
            console.log(self.err);
        });},

    created(){
    navigator.geolocation.getCurrentPosition(position => {
        this.center = {
            lat: position.coords.latitude.toFixed(4),
            lng: position.coords.longitude.toFixed(4)
        }
    }) }
    }
    </script>

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
td > button{
   margin: 0 5px;

}
@media screen and (max-width: 1300px){
   #tBtn {
   flex-direction: column;
   }
   td > *{
       margin: 5px 5px;
   }
}
@media screen and (max-width: 1000px){
   h1 {
       font-size: 1.2rem;
   }
   small{
       font-size: 0.8rem;
   }
   tr > th {
       font-size: 1rem;
   }
   tbody > tr > * {
       padding: 5px;
   }
   
}
</style>