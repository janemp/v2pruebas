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
            <v-stepper-step step="1" :complete="step > 1">Productor</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Guia de internación</v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-form ref="form1" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedItem.persona_id"
                  :items="personas"
                  item-text="carnet_identidad"
                  item-value="id"
                  label="Carnet de identidad"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  :error-messages="errorCarnet"
                  @change="getPersona()"
                  >
                </v-autocomplete>
              </v-form>
              <v-data-table
                :headers="headers"
                :items="persona"
                hide-actions
                >
                <template slot="items" slot-scope="props">
                  <tr>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item) }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.carnet_identidad + ' ' + props.item.departamento_extension.sigla }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded">
                      <span v-if="props.item.carnet_productor.length > 0">{{ props.item.carnet_productor[0].carnet }} </span>
                      <span v-else> - </span>
                    </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.email }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
                      <v-chip v-if="props.item.sustitucion_id" small color="blue" text-color="white">Sustituido</v-chip>
                      <v-chip v-if="props.item.cesaciones.length>0" small color="grey" text-color="white">Cesado</v-chip>
                      <v-chip v-if="props.item.sustitucion_id==null && props.item.cesaciones.length==0" small color="green" text-color="white">Vigente</v-chip>
                    </td>
                  </tr>
                </template>
                <template v-slot:expand="props">
                  <v-card flat color="blue-grey lighten-5">
                    <v-card-text >
                      <v-layout row wrap>
                        <v-flex xs3>
                          <v-avatar
                            size="150px">
                            <img v-bind:src="props.item.fotografia">
                          </v-avatar>
                        </v-flex>
                        <v-flex xs9>
                          <table class="v-table theme--light">
                            <tr>
                              <th class="text-xs-left">Telefono: </th>
                              <td class="text-xs-left">{{ props.item.telefono }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Estado civil: </th>
                              <td class="text-xs-left">{{ props.item.estado_civil }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Genero: </th>
                              <td class="text-xs-left">{{ (props.item.genero=='M')?'Masculino':'Femenino' }}</td>
                            </tr>                  
                            <tr>
                              <th class="text-xs-left">Telefono: </th>
                              <td class="text-xs-left">{{ props.item.telefono }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Email: </th>
                              <td class="text-xs-left">{{ props.item.email }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Domicilio: </th>
                              <td class="text-xs-left">{{ props.item.domicilio }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Fecha de nacimiento: </th>
                              <td class="text-xs-left">{{ formatDate(props.item.fecha_nacimiento) }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Pais de nacimiento: </th>
                              <td class="text-xs-left">{{ props.item.pais_nacimiento.nombre }}</td>
                            </tr>
                            <tr v-if="props.item.departamento_nacimiento_id">
                              <th class="text-xs-left">Departamento de nacimiento: </th>
                              <td class="text-xs-left">{{ props.item.departamento_nacimiento.nombre }}</td>
                            </tr>
                            <tr v-if="props.item.provincia_nacimiento_id">
                              <th class="text-xs-left">Provincia de nacimiento: </th>
                              <td class="text-xs-left">{{ props.item.provincia_nacimiento.nombre }}</td>
                            </tr>
                            <tr v-if="props.item.sustitucion_id">
                              <th colspan="2"><hr></th>
                            </tr>
                            <tr v-if="props.item.sustitucion_id">
                              <th class="text-xs-left">Sustituido por: </th>
                              <td class="text-xs-left">
                                {{ fullName(props.item.persona) }} <br>     
                                CI.: {{ props.item.persona.carnet_identidad + ' ' + props.item.persona.departamento_extension.sigla }}                   
                              </td>
                            </tr>
                            <tr v-if="props.item.sustitucion_id">
                              <th class="text-xs-left">Motivo de sustitución: </th>
                              <td class="text-xs-left">{{ props.item.sustitucion.descripcion }} </td>
                            </tr>
                            <tr v-if="props.item.sustitucion_id">
                              <th class="text-xs-left">Documento de sustitución: </th>
                              <td class="text-xs-left">
                                <v-btn icon :href="props.item.adjunto_sustitucion" target="_blank">
                                  <v-icon color="red">picture_as_pdf</v-icon>
                                </v-btn>
                              </td>
                            </tr>
                            <tr v-if="props.item.cesaciones.length>0">
                              <th colspan="2"><hr></th>
                            </tr>
                            <tr v-if="props.item.cesaciones.length>0">
                              <th class="text-xs-left">Motivo de cesación: </th>
                              <td class="text-xs-left">{{ props.item.cesaciones[0].descripcion }} </td>
                            </tr>
                            <tr v-if="props.item.cesaciones.length>0">
                              <th class="text-xs-left">Documento de cesación: </th>
                              <td class="text-xs-left">
                                <v-btn icon :href="props.item.cesaciones[0].pivot.adjunto_cesacion" target="_blank">
                                  <v-icon color="red">picture_as_pdf</v-icon>
                                </v-btn>
                              </td>
                            </tr>
                          </table>
                        </v-flex>
                      </v-layout>
                      </v-card-text>
                  </v-card>
                </template>
                <template slot="no-data">
                  <v-alert slot="no-results" :value="true" color="info" icon="fa fa-times">
                    Sin resultados.
                  </v-alert>
                </template>
              </v-data-table>
              <v-layout row wrap v-if="persona.length > 0" class="mt-2 text-md-center">
                <v-flex md6>
                  <WebCam
                    ref="webcam"
                    width="200"
                    height="auto"
                    :deviceId="deviceId"
                    @cameras="onCameras"
                    @camera-change="onCameraChange"
                    >
                  </WebCam>
                  <v-select
                    :items="devices"
                    label="Seleccione camara"
                    item-text="label"
                    item-value="deviceId"
                    v-model="camera"
                  ></v-select>
                </v-flex>
                <v-flex md6>       
                  <v-btn color="indigo" @click.passive="onCapture()">
                    <v-icon>face</v-icon> Reconocer rostro
                  </v-btn>
                  <v-progress-circular
                    indeterminate
                    color="info"
                    v-if="loading"
                  ></v-progress-circular>
                  <v-avatar
                    style="display: none"
                    tile="tile"
                    size="auto"
                    color="grey lighten-4"
                    ><img :src="img" ref="prueba"/>
                  </v-avatar>
                  <span v-if="faceMensaje">
                    <v-alert :value="reconocido" color="primary" icon="check">
                      Identificado correctamente. {{ porcentajeReconocido }} %
                    </v-alert>
                    <v-alert :value="!reconocido" color="warning" icon="close">
                      Intente nuevamente. {{ porcentajeReconocido }} %
                    </v-alert>
                  </span>        
                </v-flex>
              </v-layout>
            </v-stepper-content>
            <v-stepper-content step="2">
              <v-form ref="form2" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedItem.ruta_id"
                  :items="rutas"
                  item-text="nombre"
                  item-value="id"
                  label="Ruta"
                ></v-autocomplete>
                <v-text-field
                  v-model="selectedItem.orden_comunal"
                  label="Orden comunal"
                  :rules="[ v => (!v)?true:((v.length <= 15)?true:'No mayor a 15 caracteres')]"                  
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
                    v-model="orden_date_formatted"
                    label="Fecha de orden comunal"
                    prepend-icon="event"
                    readonly
                    slot="activator"
                    :rules="[v => !!v || 'Requerido']"
                    hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                    class="mr-2"
                  ></v-text-field>
                  <v-date-picker v-model="orden_date" no-title @input="menu = false" locale="es-bo"></v-date-picker>
                </v-menu>
                <v-text-field
                  v-model="selectedItem.numero_taques"
                  label="Numero de taques"
                  :rules="[v => !!v || 'Requerido', v => (v && v <= 100) || 'No mayor a 100']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  @input="getLibras()"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.libras"
                  label="Libras(Aprox.)"
                  :rules="[v => !!v || 'Requerido', v => (v && v <= 9999) || 'No mayor a 9999']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint                  
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.procedencia"
                  label="Procedencia"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.conductor"
                  label="Conductor"
                  :rules="[ v => (!v)?true:((v.length <= 20)?true:'No mayor a 20 caracteres')]"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.licencia"
                  label="Licencia"
                  :rules="[ v => (!v)?true:((v.length <= 20)?true:'No mayor a 20 caracteres')]"
                ></v-text-field>
                <v-autocomplete                      
                  v-model="selectedItem.vehiculo_id"
                  :items="vehiculos"
                  item-text="nombre"
                  item-value="id"
                  label="Tipo de vehículo"
                ></v-autocomplete>
                <v-autocomplete                      
                  v-model="selectedItem.marca_id"
                  :items="marcas"
                  item-text="nombre"
                  item-value="id"
                  label="Marca de vehículo"
                ></v-autocomplete>
                <v-text-field
                  v-model="selectedItem.color"
                  label="Color de vehículo"
                  :rules="[ v => (!v)?true:((v.length <= 20)?true:'No mayor a 20 caracteres')]"                  
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.numero_placa"
                  label="Placa de vehículo"
                  :rules="[ v => (!v)?true:((v.length <= 15)?true:'No mayor a 15 caracteres')]"                  
                ></v-text-field>
              </v-form>                              
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn v-if="step!=1" color="info" small @click.native="previousStep()">Anterior</v-btn>
        <v-btn :disabled="!userValid||!reconocido" color="info" small @click.native="nextStep()">Siguiente</v-btn>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn v-if="step==2" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>        
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import { WebCam } from 'vue-cam-vision'
import * as faceapi from 'face-api.js'
export default {
  components: {
    WebCam
  },  
  props: ["item", "bus"], 
  data() {
    return {
      step: 1,
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      personas: [],
      persona: [],
      errorCarnet: null,
      userValid: true,
      rutas: [],
      menu: null,
      orden_date: null,
      orden_date_formatted: null,
      vehiculos: [],
      marcas: [],
      headers: [
            {
              text: "Nombre",
              value: "primer_apellido",
              align: "center"
            },
            {
              text: "Carnet de identidad",
              value: "carnet_identidad",
              align: "center"
            },
            {
              text: "Carnet de productor",
              value: "carnet_productor[0].carnet",
              align: "center"
            },
            {
              text: "Email",
              value: "email",
              align: "center"
            },
            {
              text: "Estado",
              align: "center",
              sortable: false,
            },
          ],
      // Reconocimiento facial
      captures: [],
      imgReport: [],
      frontCam: false,
      webcam: null,
      img: null,
      camera: null,
      deviceId: null,
      devices: [],
      reconocido: null,
      faceMensaje: false,
      descriptors: { desc1: null, desc2: null },
      threshold: 0.4,
      loading: false,
      porcentajeReconocido: 0
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.orden_date_formatted = this.$moment(item.fecha_orden_comunal).format("DD/MM/YYYY")
      this.getPersona()
    });
    this.getPersonas()
    this.getRutas()
    this.getVehiculos()
    this.getMarcas()
    this.loadModels()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar'
    },
    device: function () {
      return find(this.devices, n => n.deviceId == this.deviceId)
    }
  },
  watch: {
    orden_date(val){
      this.selectedItem.fecha_orden_comunal = val
      this.orden_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
    camera: function (id) {
      this.deviceId = id
    },
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}      
      this.step = 1
      this.persona = []
      this.errorCarnet = null
      this.userValid = true
      this.$refs.form1.reset()
      this.$refs.form2.reset()
      this.captures = []
      this.imgReport = []
      this.frontCam = false
      this.webcam = null
      this.img = null
      this.camera = null
      this.deviceId = null
      this.devices = []
      this.reconocido = false
      this.faceMensaje = false   
      this.porcentajeReconocido = 0
      this.bus.$emit("closeDialog")
      
    },
    async save() {
      try {
        if (this.$refs.form2.validate()) {
          this.selectedItem.user_id = this.$store.getters["auth/user"].id
          if (this.selectedIndex != -1) {
            await axios.put("api/guia_internacion/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/guia_internacion", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getPersonas() {
      let res = await axios.get("api/persona")
      this.personas = res.data
    },
    async getPersona() {
      let res = await axios.get("api/persona/fill/" + JSON.stringify({id: this.selectedItem.persona_id}))
      this.persona = res.data
      if(res.data[0].tipo_persona_id == 1 || res.data[0].tipo_persona_id == 2 || res.data[0].activo == true){        
        if(res.data[0].sustitucion_id==null && res.data[0].cesaciones.length==0){
          this.errorCarnet = null
          this.userValid = true
        } else {
          this.errorCarnet = 'Se encuentra sustituido.'
          this.userValid = false
        }
      } else {
        this.errorCarnet = 'No es productor o productor al detalle.'
        this.userValid = false
      }
    },
    async getRutas() {
      let res = await axios.get("api/ruta")
      this.rutas = res.data
    },
    getLibras(){
        this.selectedItem.libras = this.selectedItem.numero_taques * 50 
    },
    async getVehiculos() {
      let res = await axios.get("api/vehiculo")
      this.vehiculos = res.data
    },
    async getMarcas() {
      let res = await axios.get("api/marca")
      this.marcas = res.data
    },
    async loadModels(){
      const MODEL_URL = '/models';
      await faceapi.loadTinyFaceDetectorModel(MODEL_URL);
      await faceapi.loadFaceLandmarkTinyModel(MODEL_URL);
      await faceapi.loadFaceRecognitionModel(MODEL_URL);
      await faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
      await faceapi.nets.faceLandmark68Net.loadFromUri('/models')
    },
    async onCapture () {
      this.loading = true
      this.faceMensaje = false
      this.descriptors = { desc1: null, desc2: null }
      let captura = await this.$refs.webcam.capture()
      let fotografia2 = await axios.post("api/upload/base64_image", {'base64': captura})
      this.onSelectionChanged(1, this.persona[0].fotografia)
      this.onSelectionChanged(2, fotografia2.data)
      this.updateResult()
      await axios.post("api/upload/delete", {'nombre': fotografia2.data})
    },
    async onSelectionChanged(which, uri) {
      const input = await faceapi.fetchImage(uri)
      const imgEl = this.$refs.prueba
      imgEl.src = input.src
      this.descriptors[`desc${which}`] = await faceapi.computeFaceDescriptor(input)
    },
    updateResult() {
      if(this.descriptors.desc2!=null) {
        const distance = faceapi.round(
          faceapi.euclideanDistance(this.descriptors.desc1, this.descriptors.desc2)
        )
        if (distance > this.threshold) {
          this.reconocido = false
        } else {
          this.reconocido = true
        }
        this.loading = false
        this.faceMensaje = true
        this.porcentajeReconocido = 100 - distance * 100
      }
    },
    onStarted (stream) {
      console.log('On Started Event', stream)
    },
    onStopped (stream) {
      console.log('On Stopped Event', stream)
    },
    onStop () {
      this.$refs.webcam.stop()
    },
    onStart () {
      this.$refs.webcam.start()
    },
    onError (error) {
      console.log('On Error Event', error)
    },
    onCameras (cameras) {
      this.devices = cameras
    },
    onCameraChange (deviceId) {
      this.deviceId = deviceId
      this.camera = deviceId
      console.log('On Camera Change Event', deviceId)
    },
    
    nextStep() {
      switch (this.step) {
        case 1:
          if(this.$refs.form1.validate()) {           
            this.step = 2            
          }
          break        
      }
    },
    previousStep() {
      switch (this.step) { 
        case 2:          
          this.step = 1          
          break       
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
  },  
};
</script>