<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="indigo">add_circle</v-icon>
      <span>Nuevo control</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <Retener :bus="bus"/>
          <v-form ref="form1" v-model="valid" lazy-validation>
            <v-layout row wrap>
              <v-flex md4>
              </v-flex>
              <v-flex md4>
                <p class="error">{{ error }}</p>
                <p class="decode-result"><b>Enfoque con la cámara el codigo QR.</b></p>
                <qrcode-stream v-if="dialog && qrcam" @decode="onDecode" @init="onInit" />
              </v-flex>
              <v-flex md4>
              </v-flex>
            </v-layout>            
          </v-form>
          <v-data-table
            :headers="headers"
            :items="guiasInternacion"
            hide-actions
            >
            <template slot="items" slot-scope="props">
              <tr>
                <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.correlativo }} </td>
                <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item.persona) }} </td>
                <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.persona.carnet_identidad + ' ' + props.item.persona.departamento_extension.sigla }} </td>
                <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
                  <v-chip class="caption" v-if="props.item.retenido" small color="red" text-color="white">Retenido</v-chip>
                  <v-chip v-if="props.item.controlado" small color="green darken-2" text-color="white">Controlado</v-chip>
                  <v-chip v-else small color="green lighten-2" text-color="white">Iniciado</v-chip>
                </td>
                <td class="text-xs-center">
                  <v-tooltip top>
                    <v-btn slot="activator" flat icon color="indigo" @click="save()" :disabled="props.item.controlado || props.item.retenido">
                      <v-icon>check_circle</v-icon>
                    </v-btn>
                    <span>Controlar</span>
                  </v-tooltip>
                  <v-tooltip top>
                    <v-btn slot="activator" flat icon color="indigo" @click="retenerItem(props.item)" :disabled="props.item.controlado || props.item.retenido">
                      <v-icon>cancel</v-icon>
                    </v-btn>
                    <span>Retener</span>
                  </v-tooltip>
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
                        <img v-bind:src="props.item.persona.fotografia">
                      </v-avatar>
                    </v-flex>
                    <v-flex xs9>
                      <table class="v-table theme--light">
                        <tr>
                          <th class="text-xs-left">Carnet de productor: </th>
                          <td class="text-xs-left">
                            <span v-if="props.item.persona.carnet_productor.length > 0">
                            {{ props.item.persona.carnet_productor[0].carnet }} 
                            </span>
                          </td>
                        </tr>
                        <tr v-if="props.item.ruta_id">
                          <th class="text-xs-left">Ruta: </th>
                          <td class="text-xs-left"> {{ props.item.ruta.nombre }} </td>
                        </tr>
                        <tr v-if="props.item.ruta_id">
                          <th class="text-xs-left">Puestos de control: </th>
                          <td class="text-xs-left">
                            <span v-for="(item, index) in props.item.ruta.puestos_de_control" :key="index">
                              {{ item.nombre }}<br>
                            </span>
                          </td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Orden comunal: </th>
                          <td class="text-xs-left">{{ props.item.orden_comunal }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Fecha de orden comunal: </th>
                          <td class="text-xs-left">{{ props.item.fecha_orden_comunal }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Numero de taques: </th>
                          <td class="text-xs-left">{{ props.item.numero_taques }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Libras: </th>
                          <td class="text-xs-left">{{ props.item.libras }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Procedencia: </th>
                          <td class="text-xs-left">{{ props.item.procedencia }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Conductor: </th>
                          <td class="text-xs-left">{{ props.item.conductor }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Licencia: </th>
                          <td class="text-xs-left">{{ props.item.licencia }}</td>
                        </tr>
                        <tr v-if="props.item.vehiculo_id">
                          <th class="text-xs-left">Vehículo: </th>
                          <td class="text-xs-left">{{ props.item.vehiculo.nombre }}</td>
                        </tr>
                        <tr v-if="props.item.marca_id">
                          <th class="text-xs-left">Marca: </th>
                          <td class="text-xs-left">{{ props.item.marca.nombre }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Color: </th>
                          <td class="text-xs-left">{{ props.item.color }}</td>
                        </tr>
                        <tr>
                          <th class="text-xs-left">Numero de placa: </th>
                          <td class="text-xs-left">{{ props.item.numero_placa }}</td>
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
        <v-layout row wrap v-if="guiasInternacion.length > 0" class="mt-2 text-md-center">
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
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import * as canvas from 'canvas'
import * as faceapi from 'face-api.js'
import { WebCam } from 'vue-cam-vision'
import { constants } from 'crypto';
import Retener from "./Retener"
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

export default {
  components: {
    WebCam,
    Retener,
    QrcodeStream,
    QrcodeDropZone,
    QrcodeCapture,
  },
  props: ["item", "bus"], 
  data() {
    return {
      step: 1,
      valid: false,
      dialog: false,
      qrcam: true,
      selectedIndex: -1,     
      selectedItem: {},
      personas: [],
      guiasInternacion: [],
      menu: null,
      headers: [
            {
              text: "Nro. guía de internación",
              value: "correlativo",
              align: "center"
            },
            {
              text: "Nombre",
              value: "persona.primer_apellido",
              align: "center"
            },
            {
              text: "Carnet identidad",
              value: "persona.carnet_identidad",
              align: "center"
            },
            {
              text: "Estado",
              align: "center",
              sortable: false
            },
            {
              text: "Opciones",
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
      porcentajeReconocido: 0,
      error: null,
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.dialog = true 
      this.selectedIndex = item
      this.selectedItem = item
      this.getPersona()
    });
    this.bus.$on("closeDialogQr", item => {
      this.getGuiasInternacion()
    });
    this.getPersonas()
    this.loadModels()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo control' : 'Editar control'
    },
    device: function () {
      return find(this.devices, n => n.deviceId == this.deviceId)
    }
  },
  watch: {    
    camera: function (id) {
      this.deviceId = id
    },
  },
  methods: {    
    close() {
      this.dialog = false
      this.qrcam = true
      this.valid = false
      if(this.guiasInternacion.length > 0) {
        this.onStop()
      }
      this.selectedIndex = -1
      this.selectedItem = {}      
      this.step = 1
      this.guiasInternacion = []
      this.$refs.form1.reset()
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
    async controlarItem(item) {
      this.bus.$emit("openDialogQr", item);
    },
    retenerItem(item) {
      this.bus.$emit("openDialogRetener", item);
    },
    async getPersonas() {
      let res = await axios.get("api/persona")
      this.personas = res.data
    },
    async getGuiasInternacion() {
      let res = await axios.get("api/guia_internacion/fill/" + JSON.stringify({persona_id: this.selectedItem.persona_id, controlado: false}))
      this.guiasInternacion = res.data
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
      this.onSelectionChanged(1, this.guiasInternacion[0].persona.fotografia)
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
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
    async onInit (promise) {
      try {
        await promise
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.error = "ERROR: Necesita proporcionar permisos para usar la camara"
        } else if (error.name === 'NotFoundError') {
          this.error = "ERROR: No existe una camara en este dispositivo"
        } else if (error.name === 'NotSupportedError') {
          this.error = "ERROR: Se requiere un contexto de seguridad (HTTPS, localhost)"
        } else if (error.name === 'NotReadableError') {
          this.error = "ERROR: La camara se encuentra en uso"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "ERROR: Las camaras no son compatibles"
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.error = "ERROR: La API no es soportada por su navegador"
        }
      }
    },
    async onDecode (result) {
      this.qrDecode = atob(result).split("|")
      if (this.qrDecode[0] == "INTERNACION") {
        let res = await axios.get("api/control_guia_internacion/fill/" + JSON.stringify({guia_internacion_id: this.qrDecode[1]}))
        if (res.data.length > 0) {
          this.error = "Ya fue controlado"
        } else {
          let res = await axios.get("api/guia_internacion/fill/" + JSON.stringify({id: this.qrDecode[1]}))
          this.guiasInternacion = res.data
          this.valid = true
          this.qrcam = false
        }
      } else {
        this.error = "No reconocido"
      }
    },
    async save() {
      try {
        await axios.post("api/control_guia_internacion", {guia_internacion_id: this.guiasInternacion[0].id, user_id: this.$store.getters["auth/user"].id})
        await axios.put("api/guia_internacion/" + this.guiasInternacion[0].id, {controlado: true})
        this.$toast.success('Correcto.')
        this.close();
      } catch(e) {
        console.log(e)
      }
    },
  },  
};
</script>