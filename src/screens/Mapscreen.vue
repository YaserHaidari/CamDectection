<template>
    <NavBarComp :Livecoordinates="`lat: ${center.lat}, lng: ${center.lng}`"/>
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
              id: null,
              coordinates: null,
          }
      },
      mounted(){
        this.google = window.google;
        var self = this;
        var readSQLApiURL = '/cos20031/s104608220/api/apis.php';
        fetch(readSQLApiURL)
        .then(response => response.json())
        .then(data => {
            self.DataArray = [...data]; 
            this.id = Number(this.$route.params.id);
            let foundItem = self.DataArray.find((item) => Number(item.id) === this.id);
            if(foundItem){
              this.center = {
                lat: parseFloat(foundItem.lat),
                lng: parseFloat(foundItem.lng)
              }
              this.zoom = 15;
            }
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
              return {
                  lat: parseFloat(location.lat),
                  lng: parseFloat(location.lng),
                  title: location.Road + ' road',
                  description: location.Suburb,
                  id: location.id,  
                  Upvote: location.Upvote,
                  reportedDate: location.Date,
                  reportedTime: location.Time,
                  reportedDay: location.Day
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
            <p>Camera status: UNKOWN</p>
            <h5>Reported details</h3>
            <ul>
              <li>Day: ${location.reportedDay}</li>
              <li>Time: ${location.reportedTime}</li>
              <li>Date: ${location.reportedDate}</li>
            </ul>
            <p><strong>Votes: ${location.Upvote}</strong></p>`
        });
  const marker = new this.google.maps.Marker({
    position: { lat: location.lat, lng: location.lng },
    map: this.$refs.map.map
  });
  infoWindow.open(this.$refs.map.map, marker);
},
      },
  }
  </script>