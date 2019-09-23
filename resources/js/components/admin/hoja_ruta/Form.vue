<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="indigo">add_circle</v-icon>
      <span>Nuevo</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-stepper v-model="step" vertical>
          <v-stepper-header>
            <v-stepper-step step="1" :complete="step > 1">Comercializador</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Hoja de ruta</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="3" :complete="step > 3">Definición de ruta</v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-form ref="form1" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedItem.persona_id"
                  :items="comercializadores"
                  item-text="carnet_identidad"
                  item-value="id"
                  label="Carnet de identidad"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  @change="getComercializador()"
                  >
                </v-autocomplete>
              </v-form>
              <v-card flat color="blue-grey lighten-5" v-if="comercializador !=  null">
                <v-card-text >
                  <v-layout row wrap>
                    <v-flex xs3>
                      <v-avatar
                        size="150px">
                        <img v-bind:src="comercializador.fotografia">
                      </v-avatar>
                    </v-flex>
                    <v-flex xs9>
                      <table>
                        <tr>
                          <th class="text-xs-left">Nombre: </th>
                          <td class="text-xs-left"> {{ fullName(comercializador) }} </td>
                        </tr>                  
                        <tr>
                          <th class="text-xs-left">Carnet de identidad: </th>
                          <td class="text-xs-left"> {{ comercializador.carnet_identidad + ' ' + comercializador.departamento_extension.sigla }} </td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Estado civil: </th>
                          <td class="text-xs-left"> {{ comercializador.estado_civil }} </td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Género: </th>
                          <td class="text-xs-left"> {{ comercializador.genero == 'M' ? 'Masculino' : 'Femenino' }} </td>
                        </tr>
                      </table>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-stepper-content>
            <v-stepper-content step="2">
              <v-form ref="form2" v-model="valid" lazy-validation>
                <v-text-field
                  v-model="selectedItem.codigo_comercializador"
                  label="Codigo de Comercializador"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 100) || 'No mayor a 100 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>  
                <v-autocomplete                      
                  v-model="selectedItem.puesto_venta_id"
                  :items="puestos_venta"
                  item-text="mercado"
                  item-value="id"
                  label="Puesto de venta"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  >
                </v-autocomplete>                   
                <v-text-field
                  v-model="selectedItem.taques"
                  label="Numero de taques"
                  :rules="[v => !!v || 'Requerido', v => (v && v <= 999) || 'No mayor a 3 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-menu
                  v-model="menu"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                <v-text-field
                  v-model="begin_date_formatted"
                  label="Fecha inicio validez"
                  prepend-icon="event"
                  readonly
                  slot="activator"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  class="mr-2"
                ></v-text-field>
                <v-date-picker v-model="begin_date" no-title @input="menu = false" locale="es-bo"></v-date-picker>
                </v-menu>
                <v-menu
                  v-model="menu1"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                <v-text-field
                  v-model="end_date_formatted"
                  label="Fecha fin validez"
                  prepend-icon="event"
                  readonly
                  slot="activator"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  class="mr-2"
                ></v-text-field>
                <v-date-picker v-model="end_date" no-title @input="menu1 = false" locale="es-bo"></v-date-picker>
                </v-menu>
                <v-select
                  v-model="selectedItem.procedencia_id"
                  label="Departamento de Procedencia"
                  :items="departamentos"
                  item-text="nombre"
                  item-value="id"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-select>
                <v-select
                  v-model="selectedItem.destino_id"
                  label="Departamento de Destino"
                  :items="departamentos"
                  item-text="nombre"
                  item-value="id"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-select>
                <v-autocomplete                      
                  v-model="selectedItem.localidad_id"
                  :items="localidades"
                  item-text="nombre"
                  item-value="id"
                  label="Localidad"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  >
                </v-autocomplete>
                <v-text-field
                  v-model="selectedItem.destino_coca"
                  label="Destino de la coca"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-autocomplete                      
                  v-model="selectedItem.vehiculo_id"
                  :items="vehiculos"
                  item-text="nombre"
                  item-value="id"
                  label="Vehículos"
                  >
                </v-autocomplete>
                <v-autocomplete
                  v-model="selectedItem.marca_id"
                  :items="marcas"
                  item-text="nombre"
                  item-value="id"
                  label="Marca del vehículo"
                  >
                </v-autocomplete>
                <v-text-field
                  v-model="selectedItem.numero_placa"
                  label="Numero de Placa"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.color"
                  label="Color de vehiculo"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.nombre_conductor"
                  label="Nombre del conductor"
                ></v-text-field>
              </v-form>                              
            </v-stepper-content>
            <v-stepper-content step="3">
              <v-form ref="form3" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedItem.ruta_id"
                  :items="rutas"
                  item-text="nombre"
                  item-value="id"
                  label="Elija la ruta"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  @change="getRuta()"
                  >
                </v-autocomplete>
              </v-form>
              <v-card flat color="blue-grey lighten-5">
                <v-card-text >
                  <v-layout row wrap>
                    <Gmap-Map style="width: 100%; height: 350px;" :zoom="zoom" :center="{lat: lat, lng: lng}">
                      <Gmap-Marker v-for="(marker, index) in markers"
                        :key="index"
                        ref="marker"
                        :position="marker.position"
                        icon="/img/red.png"
                        ></Gmap-Marker>
                      <Gmap-Marker v-for="(marker2, index) in markersTemp"
                        :key="index + markers.length"
                        ref="marker2"
                        :position="marker2.position"
                        icon="/img/blue.png"
                        ></Gmap-Marker>
                    </Gmap-Map> 
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn v-if="step!=1" color="info" small @click.native="previousStep()">Anterior</v-btn>
        <v-btn color="info" small @click.native="nextStep()">Siguiente</v-btn>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn v-if="step==3 || (direct==true && step==2)" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
        
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import DatetimePicker from 'vuetify-datetime-picker'
import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster'

Vue.component('GmapCluster', GmapCluster)
Vue.use(VueGoogleMaps, {
 load: {
   key: 'AIzaSyDvGqqkfpe5niGTkXhzy4QQuUKGJEbmvW0',
   libraries: 'places',
   language: 'es'
 },
})

Vue.use(DatetimePicker)

export default {
  components: {
    
  },  
  props: ["item", "bus"], 
  data() {
    return {
      step: 1,
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      comercializadores: [],
      temp: [],
      comercializador: null,
      menu: null,
      menu1: null,
      begin_date: null,
      begin_date_formatted: null,
      end_date: null,
      end_date_formatted: null,
      vehiculos: [],
      vehiculo: null,
      marcas: [],
      marca: null,
      colores: [],
      color: null,
      correlativo: null,
      departamentos: [],
      localidad: null,
      localidades:[],
      rutas: [],
      ruta: null,
      markers: [],
      markersTemp: [],
      place: null,
      lat: -16.290154,
      lng: -63.588654,
      zoom: 5,
      puestos_de_control:[],
      permanencia: [],
      error: '',
      puestos_venta: [],
      direct: false
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.begin_date_formatted = this.$moment(item.fecha_inicio).format("DD/MM/YYYY")
      this.end_date_formatted = this.$moment(item.fecha_final).format("DD/MM/YYYY")
    });
    this.bus.$on('closeDialogIniciar', () => {
      this.close();
    });
    this.getDepartamentos()
    this.getComercializadores()
    this.getVehiculos()
    this.getMarcas()
    this.getRutas() 
    this.getPuestosControl()   
    this.getLocalidades()
    this.getPuestosVenta()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar'
    },
  },
  watch: {
    begin_date(val){
      this.selectedItem.fecha_inicio = val
      this.begin_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
    end_date(val){
      this.selectedItem.fecha_final = val
      this.end_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
  },
  methods: {    
    close() {
      this.comercializador = null
      this.verifica = false;
      this.encontrado = {};
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}      
      this.step = 1
      this.direct = false
      this.$refs.form1.reset()
      this.$refs.form2.reset()      
      this.bus.$emit("closeDialog")
    },
    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form3.validate()) {
          if (this.selectedIndex != -1) {
            await axios.put("api/hoja_ruta/"+this.selectedItem.id, this.selectedItem)
          } else {
            let res = await axios.post("api/hoja_ruta", this.selectedItem)
            this.printItem(res.data)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getComercializadores() {
      let res = await axios.get("api/persona/hoja_ruta");
      this.temp = res.data
      for(var temp of this.temp){
        if(temp.poder.length == 0){
          this.comercializadores.push(temp)
        }
      }
    },
    async getComercializador() {
      let res = await axios.get("api/persona/fill/"+JSON.stringify({'id': this.selectedItem.persona_id}))
      this.comercializador = res.data[0]
      let hoja = await axios.get("api/hoja_ruta/fill/"+JSON.stringify({'persona_id': res.data[0].id}))
      if(hoja.data.length > 0){
        this.direct = true
        hoja.data[0].taques = null
        var fecha = new Date()
        this.begin_date_formatted = this.$moment(fecha).format("DD/MM/YYYY")
        this.end_date_formatted = this.$moment(fecha).format("DD/MM/YYYY")
        this.selectedItem = hoja.data[0];
      }
      else{
        this.$refs.form2.reset()
      }

    },
    async getVehiculos() {
      let res = await axios.get("api/vehiculo")
      this.vehiculos = res.data
    },
    async getMarcas() {
      let res = await axios.get("api/marca")
      this.marcas = res.data
    },
    async getLocalidades() {
      let res = await axios.get("api/localidad")
      this.localidades = res.data
    },
    async getDepartamentos(){
      let res = await axios.get("api/departamento")
      this.departamentos = res.data;
    },
    async getRutas(){
      let res = await axios.get("api/ruta")
      this.rutas = res.data;
    },
    async getRuta(){
      let res = await axios.get("api/ruta/"+this.selectedItem.ruta_id)
      this.ruta = res.data
      this.markersTemp = []
      for(var i=0; i<this.ruta.puestos_de_control.length; i++){
        this.markersTemp.push({
          id: this.ruta.puestos_de_control[i].id,
          position: {
            lat: parseFloat(this.ruta.puestos_de_control[i].latitud),
            lng: parseFloat(this.ruta.puestos_de_control[i].longitud)
          },
        });
      }
    },
    async getPuestosVenta(){
      let res = await axios.get("api/puesto_venta")
      this.puestos_venta = res.data;
    },
    async getPuestosControl(){
      let res = await axios.get("api/puesto_de_control")
      this.puestos_de_control = res.data
      for(var i = 0; i < this.puestos_de_control.length; i++){
        const createdMarker = this.addMarker();
        createdMarker.position.lat = parseFloat(this.puestos_de_control[i].latitud);
        createdMarker.position.lng = parseFloat(this.puestos_de_control[i].longitud);
        createdMarker.id = this.puestos_de_control[i].id;
      }
    },
    async getCorrelativo() {
      let res = await axios.get("api/hoja_ruta/max/correlativo")
      this.selectedItem.correlativo = res.data
    },
    async printItem(item) {
      try {        
        let res = await axios({
          method: "GET",
          url: "api/hoja_ruta/print/" + item.id,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {type: "application/pdf"});
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
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
    nextStep() {
      switch (this.step) {
        case 1:
          if(this.$refs.form1.validate()) {           
            this.step = 2            
          }
          break
        case 2:
          if (this.$refs.form2.validate()){
            this.step = 3
          }
          break         
      }
    },
    previousStep() {
      switch (this.step) {
        case 3:
          this.step = 2          
          break  
        case 2:
          this.step = 1          
          break       
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },    
  },  
};
</script>