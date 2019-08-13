<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="indigo">add_circle</v-icon>
      <span>Nuevo</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
        <v-toolbar-title class="white--text">{{ sustituirA }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-stepper v-model="step" vertical>
          <v-stepper-header>
            <v-stepper-step step="1" :complete="step > 1">Verficación</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Información</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="3" :complete="step > 3">Fotografia</v-stepper-step>
            <v-divider></v-divider>
            <!-- <v-stepper-step step="4" :complete="step > 4">Biometrico</v-stepper-step>
            <v-divider></v-divider> -->
            <v-stepper-step step="4" :complete="step > 4">Concesión de poder</v-stepper-step>
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
              <v-card flat color="blue-grey lighten-5" v-if="comercializador">
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
                      </table>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>                            
              </v-stepper-content>
              <v-stepper-content step="2">
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
                        :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
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
                        :rules="[v => !!v || 'Requerido', v => (v && v.length <= 20) || 'No mayor a 20 caracteres']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-2"
                      ></v-text-field>                    
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="selectedItem.email"
                        label="Email"
                        :error-messages="errorEmail"
                        @change="verificarEmail()"
                        :rules="[v => !!v || 'Requerido', v => /.+@.+/.test(v) || 'E-mail no es valido']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
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
                        clearable                     
                        >
                      </v-autocomplete>
                    </v-flex>
                  </v-layout>              
                </v-form>
              </v-stepper-content>
              <v-stepper-content step="3">
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
              <!-- <v-stepper-content step="4">
                <v-form ref="form3" v-model="valid" lazy-validation>
                  
                </v-form>                              
              </v-stepper-content> -->
              <v-stepper-content step="4">
                <v-form ref="form3" v-model="valid" lazy-validation>
                  <v-layout row wrap>
                    <v-flex xs6>
                      <v-textarea
                        v-model="selectedItem.motivo"
                        label="Motivo de la conseción de poder"
                        :rules="[v => !!v || 'Requerido']"
                        hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                        class="mr-2"
                      ></v-textarea>
                    </v-flex>
                    <v-flex xs6>
                      <template>
                        <vue-clip 
                          :options="options" 
                          :on-complete="complete">
                          <template slot="clip-uploader-action">                        
                            <div class="dz-message v-btn v-btn--small theme--light info">Subir archivo PDF</div>                        
                          </template>
                          <template slot="clip-uploader-body" slot-scope="props">                            
                            <div v-for="(file, index) in props.files" :key="index">
                              <v-icon color="success" v-if="file.status=='success'">check_circle</v-icon>
                              <span class="green--text" v-if="file.status=='success'"> correcto.</span>                            
                              <v-icon color="error" v-if="file.status=='error'">error</v-icon>                            
                              <span class="red--text" v-if="file.status=='error'"> {{ file.errorMessage }}</span>
                            </div>
                          </template>
                        </vue-clip>
                      </template>
                    </v-flex>
                  </v-layout>
                </v-form>                              
              </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn v-if="step!=1" color="info" small @click.native="previousStep()">Anterior</v-btn>
        <v-btn v-if="comercializador" color="info" small @click.native="nextStep()" :disabled="!valid">Siguiente</v-btn>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn v-if="step==4" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
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
      img: null,
      camera: null,
      deviceId: null,
      devices: [],
      frontCam: true,
      report: null,
      googleKey: '',
      valFotografia: null,
      getCamera: false,
      sustituir: false,
      sustituirA: null,
      idOld: null,

      options: {
        url: 'api/upload/file',
        headers: axios.defaults.headers.common,
        paramName: 'file',
        uploadMultiple: false,
        acceptedFiles: {
          extensions:  ['application/pdf'],
          message: 'Formato no permitido.'
        },
        maxFilesize: {
          limit: 5,
          message: 'El archivo excede los 5Mb.'
        },
        maxFiles: 1,
      },
      fileMotivo: null,
      verifica: false,
      carnet: '',
      encontrado: {},
      comercializadores: [],
      comercializador: null
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.sustituir = item.sustituir            
      this.dialog = true
      this.selectedIndex = item
      if(this.sustituir){
        this.sustituirA = this.fullName(item)
        this.idOld = item.id
      } else {
        this.selectedItem = item
        this.pais_nacimiento = item.pais_nacimiento.nombre
        this.birth_date_formatted = this.$moment(item.fecha_nacimiento).format("DD/MM/YYYY")
        this.img = item.fotografia
        this.valFotografia = 'Fotografia capturada.'
        this.getProvincias()
      }
    });
    this.getPaises()
    this.getDepartamentos()
    this.getProvincias()
    this.getComercializadores()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : ((this.sustituir)?'Sustituir a:':'Editar')
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
    async buscarCo(){
      var param = {'carnet_identidad': this.carnet}
      await axios.get('api/poder/fill/'+JSON.stringify(param)).then(res => {
        if(res.data.length > 0){
          this.verifica = true;
          this.encontrado = res.data[0];
        }
        else{
          this.verifica = false;
        }
      })
    },   
    close() {
      this.comercializador = null
      this.carnet = '';
      this.encontrado = {};
      this.verifica = false;
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.pais_nacimiento = null
      this.img = null
      this.step = 1
      this.$refs.form1.reset()
      this.$refs.form2.reset()
      this.$refs.form3.reset()
      this.errorCarnet = null
      this.errorEmail = null
      this.bus.$emit("closeDialog")
      this.sustituir = false
      this.sustituirA = null
      this.idOld = null
      this.fileSustituir = null
      if(this.getCamera==true){
        this.onStop()
      }
    },
    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form3.validate()) {
          var param = {'nombre': this.pais_nacimiento}
          let getPais = await axios.get("api/pais/fill/"+JSON.stringify(param))
            if(getPais.data.length > 0) {
              this.selectedItem.pais_nacimiento_id = getPais.data[0].id
            } else {
              let setpais = await axios.post("api/pais", {nombre: this.pais_nacimiento})
              this.selectedItem.pais_nacimiento_id = setPais.data.id
            }
          if (this.selectedIndex != -1) {
            if(this.sustituir) {
              await axios.put("api/persona/"+this.idOld, {sustituido: true})
              this.selectedItem.persona_id = this.idOld
              this.selectedItem.adjuntos = this.fileSustituir
              await axios.post("api/persona", this.selectedItem)
            } else {
              await axios.put("api/persona/"+this.selectedItem.id, this.selectedItem)
            }
          } else {
            this.selectedItem.tipo_persona_id = 2
            const data = {
              motivo: this.selectedItem.motivo,
              adjuntos: this.fileMotivo,
              persona: this.selectedItem,
              persona_id: this.comercializador.id,
              user_id: this.selectedItem.user_id
            }           
            await axios.post("api/poder", data)
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
      let res = await axios.get("api/provincia")
      this.provincias = res.data.filter(obj => { return obj.departamento_id == this.selectedItem.departamento_nacimiento_id;});
    },
    async verificarCarnet() {
      var param = {'carnet_identidad': this.selectedItem.carnet_identidad}
      let res = await axios.get("api/persona/fill/"+JSON.stringify(param))
      let res2 = await axios.get("api/persona/fill/"+JSON.stringify(param))
      if(res.data.length > 0 || res2.data.length > 0) {
        this.errorCarnet = "Ya existe este carnet."
      } else {
        this.errorCarnet = null
      }
    },
    async verificarEmail() {
      var param = {'email': this.selectedItem.email}
      let res = await axios.get("api/persona/fill/"+JSON.stringify(param))
      let res2 = await axios.get("api/persona/fill/"+JSON.stringify(param))
      if(res.data.length > 0 || res2.data.length > 0) {
        this.errorEmail = "Ya existe este email."
      } else {
        this.errorEmail = null
      }
    },
    async getComercializadores() {
      var param = {'tipo_persona_id': 2, 'tipo_persona_id': 4}
      let res = await axios.get("api/persona/fill/"+JSON.stringify(param));
      this.comercializadores = res.data
    },
    async getComercializador() {
      let res = await axios.get("api/persona/fill/"+JSON.stringify({'id': this.selectedItem.persona_id}))
      this.comercializador = res.data[0]
    },
    nextStep() {
      switch (this.step) {
        case 1:
          // if(this.$refs.form1.validate()) {
            this.step = 2
          // }
          break
        case 2:
          // if(this.$refs.form2.validate()) {
            this.step = 3
            if(this.getCamera==false){
              this.getCamera = true
            } else {
              this.onStart()
            }
          // }
          break
        case 3:
          // if(this.$refs.form3.validate()) {
            this.step = 4
            this.onStop()
          // }
          break
        // case 4:
        //   // if(this.$refs.form3.validate()) {
        //     this.step = 5
        //   // }
        //   break
        default:
          this.step = 1
          break
      }
    },
    previousStep() {
      switch (this.step) { 
        // case 5:
        //   this.step = 4          
        //   break
        case 4:
          this.step = 3   
          if(this.getCamera==true){
            this.onStart()
          }       
          break       
        case 3:
          this.step = 2
          if(this.getCamera==true){
            this.onStop()
          }
          break
        case 2:
          this.step = 1
          
          break        
        default:
          this.step = 1
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
    // UPLOAD
    complete (file, status, xhr) {
      this.fileMotivo = xhr.response
    }
  },
  filters: {
    percent: function (value) {
      if (!value) return ''
      return (Math.floor((value) * 10000) / 100).toFixed(0) + '%'
    }
  }
};
</script>