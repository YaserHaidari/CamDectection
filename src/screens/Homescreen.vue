<template>
    <NavBarComp class="NavBarComp" @search="handleSearch" :Livecoordinates="`lat: ${center.lat}, lng: ${center.lng}`" />
    <div class="col-12" id="headerContainer">
        <h1>Approved mobile phone & seatbelt detection camera locations in Victoria</h1>
        <small>Published & updated by <a
                href="https://www.vic.gov.au/approved-mobile-camera-locations">Vicroad</a></small>
        <button class="btn btn-warning" @click="reportCam(center.lat, center.lng)">
            <i class="fas fa-camera"></i> Report Camera here
        </button>

        <button style="margin-top: 10px;" v-if="isAdmin" class="btn btn-warning" @click="isAdmin = false">
            <i class="fas fa-exchange-alt"></i> Switch
        </button>
    </div>
    <table class="col-12" style="width: 100%;">
        <thead>
            <tr>
                <th>Road</th>
                <th>Suburb</th>
                <!-- <th v-if="!isAdmin">latitude</th>
                <th v-if="!isAdmin">Longitute</th> -->
                <th v-if="!isAdmin">Votes</th>
                <th>More Info</th>
                <th v-if="isAdmin">Admin Panel</th>
            </tr>
        </thead>
        <tbody v-for="(item, index) in filteredData" :key="index">
            <tr>
                <td v-if="!isAdmin">{{ item.Road }}</td>
                <td v-if="!isAdmin">{{ item.Suburb }}</td>
                <td v-if="isAdmin"><input type="text" v-model="newroadName[index]" :placeholder="item.Road" />
                    <button class="btn btn-success" @click="changeRoadBtnAdmin(item.Road, index)">Modify Road</button>
                </td>
                <td v-if="isAdmin"><input type="text" v-model="newsuburbName[index]" :placeholder="item.Suburb" />
                    <button class="btn btn-success" @click="changeSuburbBtn(item.Suburb, index)">Modify Suburb</button>
                </td>
<!-- 
                <td v-if="!isAdmin">{{ item.lat }}</td>
                <td v-if="!isAdmin">{{ item.lng }}</td> -->

                <td v-if="!isAdmin">Votes: {{ item.Upvote }}</td>
                <td id="tBtn" style="display: flex; flex-direction: row; flex-wrap: nowrap; min-height: 100px; align-items: center; justify-content: center;">
                    <button class="btn btn-link" @click="doMoreInfo(index)">Show</button>
                    <button style="height: 50%;" class="btn btn-success" type="submit" @click="voteBtn(index, 'UPVOTE')">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </button>
                    <button style="height: 50%;" class="btn btn-warning" type="submit" @click="voteBtn(index, 'DOWNVOTE')">
                        <i class="bi bi-hand-thumbs-down"></i>
                    </button>
                </td>
                <td v-if="isAdmin">
                    <button class="btn btn-warning" @click="deleteRowAdmin(index)">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
    <FooterComp class="FooterComp" />
</template>


<script>

import NavBarComp from '../components/NavBarComp.vue'
import axios from 'axios';
import FooterComp from '@/components/FooterComp.vue';
import { getAuth, onAuthStateChanged } from 'firebase/auth';
export default {
    name: 'HomeScreen',
    components: {
        NavBarComp,
        FooterComp
    },
    data() {
        return {
            username: '',
            filteredData: [],
            center: {},
            Data: [],
            err: '',
            msg: '',
            screenWidth: window.innerWidth,
            screenHeight: window.innerHeight,
            showLatLong: true,
            suburb: '',
            road: '',
            post_code: '',
            isAdmin: false,
            newroadName: [],
            newsuburbName: [],
        }
    },

    methods: {
        doMoreInfo(index) {
            const item = this.filteredData.filter(item => {
                return item.Road === this.filteredData[index].Road;
            })
            console.log(item[0]['id'])
            this.$router.push({ name: 'Map', params: { id: item[0]['id'] } });
        },
        handleSearch(searchText) {
            let Data = this.Data;
            searchText = String(searchText)
            if (searchText === '' || searchText === null) {
                this.filteredData = Data
            }
            const searchedData = Data.filter((item) => {
                return item.Road.toLowerCase().includes(searchText.toLowerCase())
                    || item.Suburb.toLowerCase().includes(searchText.toLowerCase())
            })
            this.filteredData = searchedData
        },
        voteBtn(id, type) {
            console.log(type)
            console.log(id + 1);
            var putSQLApiURL = `/cos20031/s104608220/api/apis2.php/${this.filteredData[id].id}`;
            let requestOptions;
            if (type === "DOWNVOTE") {
                console.log("Downvote")
                requestOptions = {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "Upvote": -1 // Example: Increment Upvote by 1
                    })
                };
            } else {
                requestOptions = {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "Upvote": 1 // Example: Increment Upvote by 1
                    })
                };
            }
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
        async reportCam(lat, lng) {
            if (lat ===  undefined || lng === undefined) {
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
                    const hasMatchingItem = this.filteredData.some(item => {
                        return item.Road === this.road && item.Suburb === this.suburb;
                    });
                    if (hasMatchingItem) {
                        alert("Camera already reported at this location");
                        return;
                    } else {
                        if (confirm(`You are reporting a camera at ${this.road}, ${this.suburb}, ${this.post_code}. Do you want to proceed?`)) {
                            const postData = {
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
                    }
                } else {
                    console.error(data.error_message);
                    throw new Error(`Geocoding error: ${data.status}`);
                }
            } catch (error) {
                console.error(error);
            }
        },
        deleteRowAdmin(id) {
            console.log(id + 1);
            console.log("btn clicked")
            var deleteSQLApiURL = `/cos20031/s104608220/api/apis4.php/${this.filteredData[id].id}`;
            const requestOptions = {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            };

            fetch(deleteSQLApiURL, requestOptions).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse response as JSON
            }).then(data => {
                console.log(data + "AHAHA");
                alert("Row deleted successfully");
                location.reload();
            }).catch(error => console.error("Error:", error));
        },
        changeRoadBtnAdmin(itemRoad, index) {
            const currentRoad = this.newroadName[index];
            console.log(this.newroadName)
            const road = this.filteredData.filter((item) => {
                return item.Road === itemRoad;
            })
            if (road.length > 0) {
                var putSQLApiURL = `/cos30043/s104608220/api/apis5.php/${road[0].id}`;
                let requestOptions = {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "Road": currentRoad // New road name
                    })
                };
                fetch(putSQLApiURL, requestOptions).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // Parse response as JSON
                }).then(data => {
                    console.log(data);
                    alert("Road updated successfully");
                    location.reload();
                }).catch(error => console.error("Error:", error));
            } else {
                console.error("Road not found");
            }
        },


        changeSuburbBtn(itemSuburb, index) {
            const currentSuburb = this.newsuburbName[index];
            console.log(this.newsuburbName)
            const suburb = this.filteredData.filter((item) => {
                return item.Suburb === itemSuburb;
            })
            if (suburb.length > 0) {
                var putSQLApiURL = `/cos30043/s104608220/api/apis6.php/${suburb[0].id}`;
                let requestOptions = {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "Suburb": currentSuburb // New road name
                    })
                };
                fetch(putSQLApiURL, requestOptions).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // Parse response as JSON
                }).then(data => {
                    console.log(data);
                    alert("Suburb updated successfully");
                    location.reload();
                }).catch(error => console.error("Error:", error));
            } else {
                console.error("Road not found");
            }
        }
    },
    mounted() {
        if (this.screenWidth < 1000) {
            this.showLatLong = false;
        }
        var self = this;
        var readSQLApiURL = '/cos20031/s104608220/api/apis.php';
        fetch(readSQLApiURL).then(response => response.json()).then(data => {
            self.Data = data;
            self.filteredData = [...data];
            console.log(self.filteredData)
            self.msg = "Successful!";
        }).catch(error => {
            self.err = error;
            console.log(self.err);
        });
    },

    created() {
        onAuthStateChanged(getAuth(), user => {
            if (user) {
                localStorage.setItem('isLoggedIn', true);
                if (user.email === 'yhaidari99@gmail.com') {
                    this.isAdmin = true;
                }
                console.log(user.email)
            } else {
                localStorage.removeItem('isLoggedIn');
                console.log("No user signed in")
                this.$router.push({ name: 'Register' });
            }
        })
        navigator.geolocation.getCurrentPosition(position => {
            this.center = {
                lat: position.coords.latitude.toFixed(4),
                lng: position.coords.longitude.toFixed(4)
            }
        })
    }
}
</script>

<style scoped>
#headerContainer {
    display: flex;
    flex: 1;
    background: linear-gradient(to right, #a7cee4, #1f9fe8);
    justify-content: center;
    align-items: center;
    height: 30vh;
    flex-direction: column;
}

h1 {
    margin-top: 10px;
    font-size: 2.4rem;
}

small {
    font-size: 1rem;
}

h1,
small {
    color: #0052C2;
    font-weight: bold;
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    width: 50%;
    text-align: center;
}

tr>th {
    background-color: #0052C2;
    color: white;
    font-size: 1.2rem;
    font-weight: 500;
    padding-left: 10px;
    text-transform: uppercase;
}

tbody>tr>* {
    padding: 10px;
    background-color: #f2f2f2;
    border: 1px solid #0052C2;
}

table {
    border-collapse: separate;
    border-spacing: 4px;
}

td>button {
    margin: 0 5px;

}

@media screen and (max-width: 1300px) {
    #tBtn {
        flex-direction: column;
    }
    h1 {
        font-size:1.3em;
    }
    td>* {
        margin: 5px 5px;
    }
}

@media screen and (max-width: 1020px) {
    h1 {
        font-size:1.1em;
    }

    small {
        font-size: 0.8rem;
    }

    tr>th {
        font-size: 1rem;
    }

    tbody>tr>* {
        padding: 5px;
    }

}

@media screen and (max-width: 700px) {
    .NavBarComp {
        min-width: 132vw;
    }
    #headerContainer{
        min-width: 132vw;
    }
    .FooterComp{
        min-width: 132vw;
    }
    
}
</style>