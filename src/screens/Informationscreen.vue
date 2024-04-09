<template>  
    <NavBarComp/>
    <GoogleMap
      api-key="AIzaSyCBOwKCfojuQAZcBOxmUulYBsfcRRtR9LU"
      map-id="cd40dee7984fbb4a"
      style="width: 100%; height: 1000px;"
      :center="center"
      :zoom="13"
    >
    <Marker :options="getMarkerOptions">
        <InfoWindow>
          <div>
            <h2>ROAD: {{ locationData.Road }}</h2>
            <p>Suburb: {{ locationData.Suburb }}</p>
            <p>LAT: {{ locationData.lat }}</p>
            <p>LONG: {{ locationData.long }}</p>
          </div>
        </InfoWindow>
    </Marker>
    </GoogleMap>
</template>

<script>
import NavBarComp from '@/components/NavBarComp.vue'
import { GoogleMap, Marker, InfoWindow } from 'vue3-google-map'
import Data from '@/assets/Data.js'
export default {
    name: "InformationScreen",
    components: {
        GoogleMap,
        Marker,
        InfoWindow,
        NavBarComp
    },
    data(){
        return{
            id: null,
            center: {},
            coordinates: null,
            locationData: null
        }
    },
    created(){
    this.id = this.$route.params.id
    let foundItem = Data.find((item, index) => index === Number(this.id));
        if (foundItem) {
            this.coordinates = {
            lat: parseFloat(foundItem.lat),
            lng: parseFloat(foundItem.long)
        }
            this.locationData = foundItem;
            this.center = this.coordinates;
        }
    },
    computed:{
    getMarkerOptions(){
        console.log(this.title); // Add this line
        return {
            position: { lat: this.coordinates.lat, lng: this.coordinates.lng },
            title: this.title,
            icon: {
                url: 'https://www.svgrepo.com/show/24105/camera-sign.svg',
                scaledSize: { width: 30, height: 30 }
            }
        }
    }
},
}
</script>