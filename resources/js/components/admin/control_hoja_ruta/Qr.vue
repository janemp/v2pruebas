<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="indigo">room</v-icon>
      <span>Marcar puesto de control</span>
    </v-tooltip>
    <ControlQr :bus="bus"/>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Identificar comercializador</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-autocomplete
          v-model="selectedItem.persona_id"
          :items="comercializadores"
          item-text="carnet_identidad"
          item-value="id"
          label="Carnet de identidad"
          :rules="[v => !!v || 'Requerido']"
          hint="<span class='blue--text'>*Requerido</span>" persistent-hint
          @change="getControlHojaRuta()"
          >
        </v-autocomplete>
        <v-layout xs12 v-if="control_hoja_rutas.length > 0 && reconocido == false">
          <v-flex xs7>
          <WebCam
            ref="webcam"
            width="100%"
            height="auto"
            >
          </WebCam>
        </v-flex>
        <v-flex xs5>
          <v-btn
            xs3
            color="primary"
            @click="onCapture()"
          > Ejecutar Reconocimiento<v-icon>camera</v-icon>
          </v-btn>
          <v-avatar
            style="display: none"
            tile="tile"
            size="auto"
            color="grey lighten-4"
          ><img :src="img" ref="prueba"/>
          </v-avatar>
          <v-btn
            v-if="reconocido == true"
            xs3
            color="success"
          >Rostro identificado exitosamente<v-icon>check</v-icon>
          </v-btn>
          <v-btn
            v-if="reconocido == false"
            xs3
            color="error"
          > El rostro no fue identificado<v-icon>close</v-icon>
          </v-btn>
        </v-flex>
        </v-layout>
        <v-btn
          v-if="reconocido == true"
          xs3
          color="success"
        > Rostro identificado exitosamente<v-icon>check</v-icon>
        </v-btn>
        <v-data-table
              :headers="headers"
              :items="control_hoja_rutas"
              :rows-per-page-items="[5,10,20,{text:'TODO',value:-1}]"
              disable-initial-sort
              class="elevation-1"
              v-if="reconocido == true">
              <template slot="items" slot-scope="props">
                <tr>
                  <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.hoja_ruta.correlativo }} </td>
                  <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item.hoja_ruta.persona) }} </td>
                  <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.hoja_ruta.persona.carnet_identidad + ' ' + props.item.hoja_ruta.persona.departamento_extension.sigla }} </td>
                  <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
                    <v-chip color="success" text-color="white" v-if="props.item.hoja_ruta.estado==false">En curso</v-chip>
                    <v-chip color="green" text-color="white" v-if="props.item.hoja_ruta.estado==true">Terminado</v-chip>
                  </td>
                  <td class="justify-center layout">
                    <v-tooltip top>
                      <v-btn slot="activator" flat icon color="indigo" @click="controlQrItem(selectedItem)">
                        <v-icon>control_camera</v-icon>
                      </v-btn>
                      <span>Control</span>
                    </v-tooltip>
                  </td>
                </tr>
              </template>
              <template v-slot:expand="props">
                <v-card>
                  <v-card-text >
                    <v-layout row wrap>
                      <v-flex xs3>
                        <v-avatar
                          size="150px">
                          <img v-bind:src="props.item.hoja_ruta.persona.fotografia">                          
                        </v-avatar>
                      </v-flex>
                      <v-flex xs9>
                        <v-data-table
                          :headers="headers2"
                          :items="puestosControl"
                          :rows-per-page-items="[5,10,20,{text:'TODO',value:-1}]"
                          disable-initial-sort
                          class="elevation-1"
                          v-if="puestosControl">
                          <template slot="items" slot-scope="props">
                            <tr>
                              <td class="text-xs-center"> {{ props.item.nombre }} </td>
                              <td class="text-xs-center"> {{ props.item.direccion }} </td>
                              <td class="text-xs-center"> 
                                  <v-switch
                                    v-model="props.item.visitado"
                                    readonly
                                  ></v-switch>
                              </td>
                              <td class="text-xs-center"> {{ props.item.fecha }} </td>
                            </tr>
                          </template>
                          <template slot="no-data">
                            <v-alert slot="no-results" :value="true" color="info" icon="fa fa-times">
                              Sin Puestos de control.
                            </v-alert>
                          </template>
                        </v-data-table> 
                      </v-flex>
                    </v-layout>
                    </v-card-text>
                </v-card>
              </template> 
              <template slot="no-data">
                <v-alert slot="no-results" :value="true" color="info" icon="fa fa-times">
                  Sin Hojas de ruta.
                </v-alert>
              </template>
            </v-data-table>
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
import VueClip from 'vue-clip'
import ControlQr from './ControlQr'
// Librerias para el reconocimiento facial
import * as canvas from 'canvas'
import * as faceapi from 'face-api.js'
import { WebCam } from 'vue-cam-vision'


Vue.use(VueClip)

export default {
  components: {
    ControlQr,
    WebCam
  },  
  props: ["item", "bus"], 
  data() {
    return {
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      comercializadores: [],
      control_hoja_rutas: [],
      puestosControl: [],
      headers: [
          {
            text: "Correlativo",
            value: "hoja_ruta.correlativo",
            align: "center"
          },
          {
            text: "Nombre",
            value: "persona.primer_apellido",
            align: "center"
          },
          {
            text: "Carnet de identidad",
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
            sortable: false
          }
        ],
        headers2: [
          {
            text: "Nombre",
            value: "nombre",
            align: "center"
          },
          {
            text: "Dirección",
            value: "direccion",
            align: "center"
          },
          {
            text: "Estado",
            value: "Estado",
            align: "center"
          },
          {
            text: "Fecha y hora de visita",
            value: "created_at",
            align: "center",
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
        reconocido: false
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("closeDialogQr", item => {
      this.selectedItem = item;
      this.close()
    });   
    this.getComercializadores()
    this.loadModels()
  },
  computed: {
  },
  watch: {   
  },
  methods: { 
    async loadModels(){
      const MODEL_URL = '/models';
      await faceapi.loadTinyFaceDetectorModel(MODEL_URL);
      await faceapi.loadFaceLandmarkTinyModel(MODEL_URL);
      await faceapi.loadFaceRecognitionModel(MODEL_URL);
      await faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
      await faceapi.nets.faceLandmark68Net.loadFromUri('/models')
    },   
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.hojasRutas = []
      this.control_hoja_rutas = []
      this.puestosControl = []
      this.bus.$emit("closeDialog")
      this.reconocido = false
    },
    imprimirItem(item) {
      this.bus.$emit("openDialogImprimir", item);
    },
    
    controlQrItem(item){
      this.bus.$emit("openDialogQr", item);
    },
    async getComercializadores() {
      var param1 = {'tipo_persona_id': 2}
      let res1 = await axios.get("api/persona/fill/"+JSON.stringify(param1));
      var param2 = {'tipo_persona_id': 4}
      let res2 = await axios.get("api/persona/fill/"+JSON.stringify(param2));
      this.comercializadores = res1.data.concat(res2.data)
    },
    async getControlHojaRuta() {
      var param = {'persona_id': this.selectedItem.persona_id, 'estado': false, 'control': false}
      let res = await axios.get("api/hoja_ruta/fill/"+JSON.stringify(param))
      if(res.data.length > 0){
        var param2 = {'hoja_ruta_id': res.data[0].id}
        let res2 = await axios.get("api/control_hoja_ruta/fill/"+JSON.stringify(param2))
        this.control_hoja_rutas = res2.data
        for(var i=0; i<this.control_hoja_rutas[0].hoja_ruta.ruta.puestos_de_control.length; i++){
            var param = {"control_id": this.control_hoja_rutas[0].id, "puesto_id": this.control_hoja_rutas[0].hoja_ruta.ruta.puestos_de_control[i].id}
            let res = await axios.get('api/control_puesto/fill/'+JSON.stringify(param));
            if(res.data.length > 0){
              this.control_hoja_rutas[0].hoja_ruta.ruta.puestos_de_control[i].visitado = true
              this.control_hoja_rutas[0].hoja_ruta.ruta.puestos_de_control[i].fecha = res.data[0].created_at
            }
            else{
              this.control_hoja_rutas[0].hoja_ruta.ruta.puestos_de_control[i].visitado = false
              this.control_hoja_rutas[0].hoja_ruta.ruta.puestos_de_control[i].fecha = 'No visitado'
            }
          }
          this.puestosControl = this.control_hoja_rutas[0].hoja_ruta.ruta.puestos_de_control
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },    
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
    formatDateTime(date){
      return this.$moment(date).format("DD/MM/YYYY HH:mm")
    },
    // Captura y reconocimiento facial
    async onCapture () {
        this.reconocido = false
        this.img = await this.$refs.webcam.capture()
        // Deteccion de rostros
        const input = this.$refs.prueba
        let fullFaceDescriptions = await faceapi.detectAllFaces(input).withFaceLandmarks().withFaceDescriptors()
        const displaySize = { width: input.width, height: input.height }
        // Reconocimiento de rostros
        const labels = [this.control_hoja_rutas[0].hoja_ruta.persona.fotografia]
        const labeledFaceDescriptors = await Promise.all(
            labels.map(async label => {
                const imgUrl = label
                const img = await faceapi.fetchImage(imgUrl)
                const fullFaceDescription = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
                if (!fullFaceDescription) {
                throw new Error(`no faces detected for ${label}`)
                }
                const faceDescriptors = [fullFaceDescription.descriptor]
                return new faceapi.LabeledFaceDescriptors(label, faceDescriptors)
            })
        )
        const maxDescriptorDistance = 0.6
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, maxDescriptorDistance)
        const results = fullFaceDescriptions.map(fd => faceMatcher.findBestMatch(fd.descriptor))
        results.forEach((bestMatch, i) => {
          const box = fullFaceDescriptions[i].detection.box
          const text = bestMatch.toString()
          if(text.indexOf(labels[0]) > -1){
            this.reconocido = true
            this.onStop()
          }
        })
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
        console.log('On Cameras Event', cameras)
      },
      onCameraChange (deviceId) {
        this.deviceId = deviceId
        this.camera = deviceId
        console.log('On Camera Change Event', deviceId)
      }  
  },
};
</script>