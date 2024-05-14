<template>
    <NavBarComp/>
    <GoogleMap
    ref="map"
      api-key="AIzaSyCBOwKCfojuQAZcBOxmUulYBsfcRRtR9LU"
      map-id="cd40dee7984fbb4a"
      style="width: 100%; height: 1000px;"
      :center="center"
      :zoom="zoom"
      :options="mapOptions"
    >
      <Marker v-for="(location, index) in locations" :key="index"
       :options="getMarkerOptions(location)" @click="onMarkerClick(location)" />
    </GoogleMap>
    <GmapInfoWindow
  :opened="infoWindowOpened"
  :position="infoWindowPosition"
  @closeclick="infoWindowOpened = false"
>
  <div>{{ infoWindowText }}</div>
</GmapInfoWindow>
  </template>
  
  <script>
  import { GoogleMap, Marker } from 'vue3-google-map'
  import NavBarComp from '@/components/NavBarComp.vue'
  import axios from 'axios';
  // import Data from '@/assets/Data.js'
  export default {
      name: 'MapScreen',
      components: {
          GoogleMap,
          Marker,
          NavBarComp
      },
      data(){
          return{
            google: null,
              username: '',
              DataArray: [],
              center: { lat: -37.986, lng: 145.214 },
              zoom: 13,
              infoWindowOpened: false,
              infoWindowPosition: null,
              infoWindowText: '',
              Upvotes: 0,
              suburb: null,
              road: null
          }
      },
      mounted(){
        this.google = window.google;
        this.reverseGeocode(this.center.lat, this.center.lng);
        console.log(this.sub)
        var self = this;
        var readSQLApiURL = '/cos20031/s104608220/api/apis.php';
        fetch(readSQLApiURL)
        .then(response => response.json())
        .then(data => {
            self.DataArray = [...data]; 
            console.log('heres data set')
            console.log(self.DataArray);
            self.msg = "Successful!";
        }).catch(error => {
            self.err = error;
            console.log(self.err);
        });
      },
      computed: {
          mapOptions() {
              return {
                  mapId: 'cd40dee7984fbb4a',
                  disableDefaultUI: true,
              }
          },
        locations(){
          return this.DataArray.map((location) => {
            console.log(location);
            console.log(this.DataArray)
              return {
                  lat: parseFloat(location.lat),
                  lng: parseFloat(location.lng),
                  title: location.Road + ' road',
                  description: location.Suburb,
                  id: location.id,  
                  Upvote: location.Upvote
                }
          })
         },
         getMarkerOptions() {
              return (location) => {
                  return {
                      position: { lat: location.lat, lng: location.lng },
                      title: location.title,
                      icon: {
                          url: 'https://cdn-icons-png.flaticon.com/512/8742/8742260.png',
                          scaledSize: { width: 25, height: 25 }
                      },
                  }
              }
          },
      },
      methods:{
        onMarkerClick(location) {
        this.center = { lat: location.lat, lng: location.lng }
        this.zoom++;
        if(this.zoom > 20){
            this.zoom = 13
        }
        const infoWindow = new this.google.maps.InfoWindow({
            content: `<h1>${location.title}</h1>
            <p>Surburb: ${location.description}</p>
            <p>Id: ${location.id}</p>
            <p>Camera status: Live</p>
            <p>Last report data: 12/12/2021</p>
            <p>Upvotes: ${location.Upvote}</p>`
        });
        const marker = new this.google.maps.Marker({
        position: { lat: location.lat, lng: location.lng },
        map: this.$refs.map.map
        });
        infoWindow.open(this.$refs.map.map, marker);
        },
        async reverseGeocode(lat, lng) {
    try {
      const response = await axios.get(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyCBOwKCfojuQAZcBOxmUulYBsfcRRtR9LU`);
      const data = response.data;

      if (data.status === 'OK') {
        const components = data.results[0].address_components;
        const suburb = components.find(component => component.types.includes('locality'));
        const road = components.find(component => component.types.includes('route'));

        this.suburb = suburb ? suburb.long_name : null;
        this.road = road ? road.long_name : null;
      } else {
        throw new Error(`Geocoding error: ${data.status}`);
      }
    } catch (error) {
      console.error(error);
    }
  }
      },
  }
  </script>