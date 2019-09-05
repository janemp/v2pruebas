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
                  :items="municipios"
                  item-text="nombre"
                  item-value="id"
                  v-model="selectedItem.municipio_id"
                  label="Municipio"
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
                  v-model="selectedItem.origenla"
                  label="Origen:  Latitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.origenln"
                  label="Origen: Longitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.destinola"
                  label="Destino: Latitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.destinoln"
                  label="Destino: Longitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>              
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm4 md4>
              <v-card-text>                
                  <span>Elija los puntos de control de la ruta de forma ordenada desde el origen hasta el destino</span>
                  <Gmap-Map style="width: 260px; height: 330px;" :zoom="zoom" :center="{lat: lat, lng: lng}">
                    <Gmap-Marker v-for="(marker, index) in markers"
                      :key="index"
                      ref="marker"
                      :position="marker.position"
                      icon="/img/red.png"
                      @click="marcar(marker)"
                      @mouseover="statusText = marker.text"
                      @mouseout="statusText = null"
                      ></Gmap-Marker>
                      <div slot="visible">
                        <div style="bottom: 0; left: 0; background-color: #0000FF; color: white; position: absolute; z-index: 100">
                          {{statusText}}
                        </div>
                      </div>
                    <Gmap-Marker v-for="(marker2, index) in markersTemp"
                      :key="index + markers.length"
                      ref="marker2"
                      :position="marker2.position"
                      icon="/img/blue.png"
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
      selectedItem: {
        puestos_de_control:[]
      },
      municipios: [],
      markers: [],
      markersTemp: [],
      place: null,
      lat: -16.290154,
      lng: -63.588654,
      zoom: 5,
      puestos_de_control:[],
      permanencia: [],
      error: '',
      statusText: ''
    };
  },
  created() {},  
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;
      this.zoom = 5;
      
      this.markersTemp = [];
      for(var i = 0; i < item.puestos_de_control.length; i++){
        this.markersTemp.push({
          id: item.puestos_de_control[i].id,
          position: {
            lat: parseFloat(item.puestos_de_control[i].latitud),
            lng: parseFloat(item.puestos_de_control[i].longitud)
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
        this.permanencia.push(item.puestos_de_control[i].id);
      }
      this.selectedItem.origenla = parseFloat(this.markersTemp[0].position.lat);
      this.selectedItem.origenln = parseFloat(this.markersTemp[0].position.lng);
      this.selectedItem.origen_id = this.markersTemp[0].id
      this.selectedItem.destinola = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lat);
      this.selectedItem.destinoln = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lng);
      this.selectedItem.destino_id = this.markersTemp[this.markersTemp.length - 1].id;
      
    });
    this.getEntryData();
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
    marcar(marker){
      this.markersTemp.push({
        id: marker.id,
        position: {
          lat: marker.position.lat,
          lng: marker.position.lng
        },
      });
      this.selectedItem.origenla = parseFloat(this.markersTemp[0].position.lat);
      this.selectedItem.origenln = parseFloat(this.markersTemp[0].position.lng);
      this.selectedItem.origen_id = this.markersTemp[0].id
      this.selectedItem.destinola = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lat);
      this.selectedItem.destinoln = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lng);
      this.selectedItem.destino_id = this.markersTemp[this.markersTemp.length - 1].id;
      this.selectedItem.puestos_de_control.push(marker.id);
    },
    async getEntryData() {
      let res = await axios.get("api/municipio")
      this.municipios = res.data;
      let response = await axios.get("api/puesto_de_control");
      this.puestos_de_control = response.data;
      for(var i = 0; i < this.puestos_de_control.length; i++){
        const createdMarker = this.addMarker();
        createdMarker.position.lat = parseFloat(this.puestos_de_control[i].latitud);
        createdMarker.position.lng = parseFloat(this.puestos_de_control[i].longitud);
        createdMarker.text = this.puestos_de_control[i].nombre;
        createdMarker.id = this.puestos_de_control[i].id;
      }
    },
    close() {
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialog");
      this.selectedIndex = -1;
      this.selectedItem = {
        puestos_de_control:[]
      };
      this.markersTemp = [];
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          if (this.selectedIndex != -1) {
            this.permanencia = []
            for(let temp of this.markersTemp){
              this.permanencia.push(temp.id)
            }
            this.selectedItem.puestos_de_control = this.permanencia
            console.log(this.permanencia)
            await axios.put("api/ruta/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/ruta", this.selectedItem)
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
      this.markers.push({
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
      axios.get('/api/ruta/codigo/'+this.selectedItem.codigo).then(response =>{
        this.error = response.data;
      });
    }
  }
};
</script>