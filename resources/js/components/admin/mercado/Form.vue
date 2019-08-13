<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout wrap>
            <v-flex xs12 sm4 md4>
              <v-card-text>
                <v-select
                  :items="departamentos"
                  item-text="nombre"
                  item-value="id"
                  v-model="selectedItem.departamento_id"
                  label="Departamento"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-select>
                <v-text-field
                  v-model="selectedItem.codigo"
                  label="Codigo"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 5) || 'No mayor a 5 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  :error-messages="error"
                  @change="codevalidate()"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.nombre"
                  label="Nombre"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 100) || 'No mayor a 100 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-textarea
                  v-model="selectedItem.descripcion"
                  label="Descripción"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-textarea>
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm4 md4>
              <v-card-text>                
                <v-text-field
                  v-model="selectedItem.latitud"
                  label="Latitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.longitud"
                  label="Longitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-textarea
                  v-model="selectedItem.direccion"
                  label="Dirección"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-textarea>               
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm4 md4>
              <v-card-text>                
                    <gmap-autocomplete
                      placeholder="Introduzca un lugar"
                      @place_changed="setPlace">
                    </gmap-autocomplete>
                  <br/>

                  <Gmap-Map style="width: 260px; height: 350px;" :zoom="zoom" :center="{lat: lat, lng: lng}" @click="mapRclicked">
                    <Gmap-Marker v-for="(marker, index) in markers"
                      :key="index"
                      :position="marker.position"
                      ></Gmap-Marker>
                    <Gmap-Marker
                      v-if="this.place"
                      label=""
                      :position="{
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng(),
                      }"
                      ></Gmap-Marker>
                  </Gmap-Map> 
              </v-card-text>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster'

Vue.component('GmapCluster', GmapCluster)
Vue.use(VueGoogleMaps, {
 load: {
   key: 'AIzaSyBThvlm_swZHMIHmwqYOidrWr8pQStvSsM',
   libraries: 'places',
   language: 'es'
 },
})

export default {
  props: ["item", "bus"],  
  data() {
    return {      
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      departamentos: [],
      markers: [],
      place: null,
      lat: -16.290154,
      lng: -63.588654,
      zoom: 5,
      error: ''
    };
  },
  created() {},  
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;
      this.lat = parseFloat(item.latitud);
      this.lng = parseFloat(item.longitud);
      this.zoom = 15;
      this.markers = [];
      this.markers.push({
        id: this.lastId,
        position: {
          lat: parseFloat(item.latitud),
          lng: parseFloat(item.longitud)
        },
        opacity: 1,
        draggable: true,
        enabled: true,
        clicked: 0,
        rightClicked: 0,
        dragended: 0,
        ifw: true,
        ifw2text: 'This text is bad please change me :( '
      });
    });
    this.getDepartamentos()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
    async getDepartamentos() {
      let res = await axios.get("api/departamento")
      this.departamentos = res.data
    },
    close() {
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialog");
      this.selectedIndex = -1;
      this.selectedItem = {}
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          if (this.selectedIndex != -1) {
            await axios.put("api/mercado/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/mercado", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    setDescription(description) {
      this.description = description;
    },
    setPlace(place) {
      this.markers = [];
      //this.place = place
      this.selectedItem.latitud = place.geometry.location.lat();
      this.selectedItem.longitud = place.geometry.location.lng();
      this.lat = place.geometry.location.lat();
      this.lng = place.geometry.location.lng();
      this.zoom = 12;
    },
    usePlace(place) {
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
        })
        this.place = null;
      }
    },
    mapRclicked(mouseArgs) {
      const createdMarker = this.addMarker();
      createdMarker.position.lat = mouseArgs.latLng.lat();
      createdMarker.position.lng = mouseArgs.latLng.lng();
      this.selectedItem.latitud = mouseArgs.latLng.lat();
      this.selectedItem.longitud = mouseArgs.latLng.lng();
    },
    addMarker: function addMarker() {
      this.lastId++;
      this.markers = [];
      this.markers.push({
        id: this.lastId,
        position: {
          lat: 48.8538302,
          lng: 2.2982161
        },
        opacity: 1,
        draggable: true,
        enabled: true,
        clicked: 0,
        rightClicked: 0,
        dragended: 0,
        ifw: true,
        ifw2text: 'This text is bad please change me :( '
      });
      return this.markers[this.markers.length - 1];
    },
    codevalidate(){
      axios.get('/api/mercado/codigo/'+this.selectedItem.codigo).then(response =>{
        this.error = response.data;
      });
    }
  },  
};
</script>