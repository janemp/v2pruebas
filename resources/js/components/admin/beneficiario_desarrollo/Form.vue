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
            <v-stepper-step step="1" :complete="step > 1">Información general</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Fotografía</v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
              <v-stepper-content step="1">
                <v-form ref="form1" v-model="valid" lazy-validation>
                  <v-layout row wrap>
                    <v-flex xs4>
                      <v-text-field
                        v-model="selectedItem.nombre"
                        label="Nombre"
                        :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-3"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs4>
                      <v-text-field
                        v-model="selectedItem.primer_apellido"
                        label="Primer apellido"
                        :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-3"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs4>
                      <v-text-field
                        v-model="selectedItem.segundo_apellido"
                        label="Segundo apellido"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <v-text-field
                        v-model="selectedItem.carnet_identidad"
                        label="Carnet de identidad"
                        :rules="[v => !!v || 'Requerido', v => (v && v.length <= 20) || 'No mayor a 20 caracteres']"
                        :error-messages="errorCarnet"
                        @change="verificarCarnet()"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-2"
                      ></v-text-field>                    
                    </v-flex>
                    <v-flex xs6>
                      <v-autocomplete                      
                        v-model="selectedItem.departamento_extension_id"
                        :items="departamentos"
                        item-text="sigla"
                        item-value="id"
                        label="Extension"
                        :rules="[v => !!v || 'Requerido']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-2"
                        >
                      </v-autocomplete>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <v-autocomplete                      
                        v-model="selectedItem.estado_civil"
                        :items="estadoCivil"
                        label="Estado civil"
                        :rules="[v => !!v || 'Requerido']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-2"
                        >
                      </v-autocomplete>                   
                    </v-flex>
                    <v-flex xs6>
                      <v-autocomplete                      
                        v-model="selectedItem.genero"
                        :items="generos"
                        item-text="nombre"
                        item-value="valor"
                        label="Genero"
                        :rules="[v => !!v || 'Requerido']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-2"
                        >
                      </v-autocomplete>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <v-text-field
                        v-model="selectedItem.telefono"
                        label="Telefono/Celular"
                        class="mr-2"
                      ></v-text-field>                    
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="selectedItem.email"
                        label="Email"
                        :error-messages="errorEmail"
                        @change="verificarEmail()"                        
                        class="mr-2"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-text-field
                    v-model="selectedItem.domicilio"
                    label="Domicilio"
                    :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
                    hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  ></v-text-field>
                  <v-layout row wrap>
                    <v-flex xs6>
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
                          v-model="birth_date_formatted"
                          label="Fecha de nacimiento"
                          prepend-icon="event"
                          readonly
                          slot="activator"
                          :rules="[v => !!v || 'Requerido']"
                          hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                          class="mr-2"
                        ></v-text-field>
                        <v-date-picker v-model="birth_date" no-title @input="menu = false"></v-date-picker>
                      </v-menu>
                    </v-flex>
                    <v-flex xs6>
                      <v-combobox
                        v-model="pais_nacimiento"
                        :items="paises"
                        label="País de nacimiento"
                        :rules="[v => !!v || 'Requerido']"
                        hint="<span class='blue--text'>*Requerido, Puede crear un nuevo registro de país.</span>" persistent-hint
                        class="mr-2"
                      ></v-combobox>
                    </v-flex> 
                  </v-layout> 
                  <v-layout row wrap>
                    <v-flex xs6>
                      <v-autocomplete                      
                        v-model="selectedItem.departamento_nacimiento_id"
                        :items="departamentos"
                        item-text="nombre"
                        item-value="id"
                        label="Departamento de nacimiento"                        
                        @change="getProvincias()"
                        class="mr-2" clearable
                        >
                      </v-autocomplete>
                    </v-flex>
                    <v-flex xs6>
                      <v-autocomplete                      
                        v-model="selectedItem.provincia_nacimiento_id"
                        :items="provincias"
                        item-text="nombre"
                        item-value="id"
                        label="Provincia de nacimiento" 
                        @change="validProvincia()"
                        clearable                     
                        >
                      </v-autocomplete>
                    </v-flex>
                  </v-layout>
                </v-form>
              </v-stepper-content>             
              <v-stepper-content step="2">
                <v-form ref="form2" v-model="valid" lazy-validation>
                  <v-layout row wrap>
                    <v-flex xs12 md6>
                      <v-card dark color="primary" height=380 v-if="getCamera">
                        <v-card-title>
                          <v-icon large left>
                            camera
                          </v-icon>                          
                          <span class="title font-weight-light" v-if="device">
                          {{ device.label }}
                          </span>
                        </v-card-title>
                        <v-card-text class="px-0">
                          <web-cam
                            style="margin: -130px 0 0 0;"
                            ref="webcam"
                            width="100%"
                            :deviceId="deviceId"
                            @cameras="onCameras"
                            @camera-change="onCameraChange"
                            :debug="false"
                            :googleKey="googleKey"
                          />
                        </v-card-text>
                      </v-card>
                    </v-flex>
                    <v-flex xs12 md6>
                      <v-card>
                        <v-avatar
                          tile="tile"
                          size="100%"
                          color="grey lighten-4"
                        ><img :src="img" />
                        </v-avatar>
                        <v-card-title primary-title>
                          <v-text-field
                            v-model="valFotografia"
                            :rules="[v => !!v || 'Requerido']"
                            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                            readonly
                            box
                          ></v-text-field>
                        </v-card-title>                      
                      </v-card>
                    </v-flex>
                    <v-select
                      xs4
                      :items="devices"
                      label="Seleccione camara"
                      item-text="label"
                      item-value="deviceId"
                      v-model="camera"
                    ></v-select>
                    <v-btn
                      xs3
                      small
                      color="primary"
                      @click="onCapture"
                    >Capturar foto <v-icon>camera</v-icon>
                    </v-btn>               
                  </v-layout>
                </v-form>         
              </v-stepper-content>
              
              
          </v-stepper-items>
        </v-stepper>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn v-if="step!=1" color="info" small @click.native="previousStep()">Anterior</v-btn>
        <v-btn color="info" small @click.native="nextStep()">Siguiente</v-btn>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn v-if="step==2" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import VueClip from 'vue-clip'
import {WebCam} from 'vue-cam-vision'

Vue.use(VueClip)

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
      pais_nacimiento: null,
      menu: null,
      errorCarnet: null,
      errorEmail: null,
      birth_date: null,
      birth_date_formatted: null,
      estadoCivil: ['Soltero', 'Casado', 'Divorciado', 'Viudo'],
      generos: [
        {
          nombre: "MASCULINO",
          valor: "M"
        },
        {
          nombre: "FEMENINO",
          valor: "F"
        },
      ],
      paises: [],
      departamentos: [],
      provincias: [],
      tiposPersona: [],      
      
      //camara
      img: null,
      camera: null,
      deviceId: null,
      devices: [],
      frontCam: true,
      report: null,
      googleKey: '',
      valFotografia: null,
      getCamera: false,      
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => { 
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.pais_nacimiento = item.pais_nacimiento.nombre
      this.birth_date_formatted = this.$moment(item.fecha_nacimiento).format("DD/MM/YYYY")
      this.img = item.fotografia
      this.valFotografia = 'Fotografia capturada.'
      this.getProvincias()
    });
    this.getPaises()
    this.getDepartamentos()
    this.getProvincias()
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
    birth_date(val){
      this.selectedItem.fecha_nacimiento = val
      this.birth_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
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
      this.selectedEmpresa = {}
      this.pais_nacimiento = null
      this.img = null
      this.step = 1
      this.errorCarnet = null
      this.errorEmail = null      
      
      if(this.getCamera==true){
        this.onStop()
      }
      this.$refs.form1.reset()
      this.$refs.form2.reset()
      this.bus.$emit("closeDialog")
    },
    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form2.validate()) {
          let getPais = await axios.get("api/pais/fill/"+ JSON.stringify({'nombre': this.pais_nacimiento}))
          if(getPais.data.length > 0) {
            this.selectedItem.pais_nacimiento_id = getPais.data[0].id
          } else {
            let setPais = await axios.post("api/pais", {nombre: this.pais_nacimiento})
            this.selectedItem.pais_nacimiento_id = setPais.data.id
          }
          if (this.selectedIndex != -1) {            
            await axios.put("api/persona/"+this.selectedItem.id, this.selectedItem)
          } else {
            this.selectedItem.tipo_persona_id = 7         
            let res = await axios.post("api/persona", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }        
      } catch(e) {
        console.log(e)
      }
    },
    async getPaises() {
      let res = await axios.get("api/pais")
      for(let pais of res.data) {
        this.paises.push(pais.nombre)
      }
    },
    async getDepartamentos() {
      let res = await axios.get("api/departamento")
      this.departamentos = res.data
    },
    async getProvincias() {
      if(!this.selectedItem.departamento_nacimiento_id){
        this.selectedItem.departamento_nacimiento_id = null
      }
      let res = await axios.get("api/provincia/fill/" + JSON.stringify({'departamento_id': this.selectedItem.departamento_nacimiento_id}))
      this.provincias = res.data
    },
    validProvincia() {
      if(!this.selectedItem.provincia_nacimiento_id){
        this.selectedItem.provincia_nacimiento_id = null
      }
    },
    async getTiposPersona() {
      let res = await axios.get("api/tipo_persona")
      this.tiposPersona = res.data
    },
    async verificarCarnet() {
      let res = await axios.get("api/persona/fill/"+ JSON.stringify({'carnet_identidad': this.selectedItem.carnet_identidad}))
      if(res.data.length > 0) {
        this.errorCarnet = "Ya existe este carnet."
      } else {
        this.errorCarnet = null
      }
    },
    async verificarEmail() {
      let res = await axios.get("api/persona/fill/"+ JSON.stringify({'email': this.selectedItem.email}))
      if(res.data.length > 0) {
        this.errorEmail = "Ya existe este email."
      } else {
        this.errorEmail = null
      }
    },
    
    
    
    nextStep() {
      switch (this.step) {
        case 1:
          if(this.$refs.form1.validate()) {
            this.step = 2
            if(this.getCamera==false){
              this.getCamera = true
            } else {
              this.onStart()
            }      
          }
          break       
        default:
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
          if(this.getCamera==true){
            this.onStop()
          }
          break        
        default:
          break
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    
    //camera
    async onCapture () {
      this.img = await this.$refs.webcam.capture()
      let filePng = await axios.post("api/upload/base64_image", {'base64': this.img})
      this.selectedItem.fotografia = filePng.data
      this.selectedItem.biometrico = filePng.data
      this.selectedItem.carnet_escaneado = filePng.data
      this.valFotografia = 'Fotografia capturada'
    },    
    onStop () {
      this.$refs.webcam.stop()
    },
    onStart () {
      this.$refs.webcam.start()
    },
    onCameras (cameras) {
      this.devices = cameras
    },
    onCameraChange (deviceId) {
      this.deviceId = deviceId
      this.camera = deviceId
    },
  },
  filters: {
    percent: function (value) {
      if (!value) return ''
      return (Math.floor((value) * 10000) / 100).toFixed(0) + '%'
    }
  }
};
</script>