<template>
    <NavBarComp/>
    <GoogleMap
      api-key="AIzaSyCBOwKCfojuQAZcBOxmUulYBsfcRRtR9LU"
      map-id="cd40dee7984fbb4a"
      style="width: 100%; height: 1000px;"
      :center="center"
      :zoom="13"
      :options="mapOptions"
    >
      <Marker v-for="(location, index) in locations" :key="index"
       :options="getMarkerOptions(location)" @click="onMarkerClick(location)" />
    </GoogleMap>
  </template>
  
  <script>
  import { GoogleMap, Marker } from 'vue3-google-map'
  import NavBarComp from '@/components/NavBarComp.vue'
  import Data from '@/assets/Data.js'
  export default {
      name: 'MapScreen',
      components: {
          GoogleMap,
          Marker,
          NavBarComp
      },
      data(){
          return{
              username: '',
              DataArray: Data,
              center: { lat: -37.986, lng: 145.214 },
          }
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
                  lng: parseFloat(location.long),
                  title: location.Road
              }
          })
         },
         getMarkerOptions() {
              return (location) => {
                  return {
                      position: { lat: location.lat, lng: location.lng },
                      title: location.title,
                      icon: {
                          url: 'https://www.svgrepo.com/show/24105/camera-sign.svg',
                          scaledSize: { width: 50, height: 50 }
                      }
                  }
              }
          },
      },
    //   methods:{
    //       onMarkerClick(location) {
    //           alert('You clicked on the marker ' + location.title)
    //       },
    //   },
  }
  </script>