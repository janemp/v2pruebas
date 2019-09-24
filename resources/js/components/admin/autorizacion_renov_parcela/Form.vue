<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-stepper v-model="step" vertical>
          <v-stepper-header>
            <v-stepper-step step="1" :complete="step > 1">Productor</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Datos de la Parcela de Coca</v-stepper-step>
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
                hide-actions>
                <template slot="items" slot-scope="props">
                  <tr>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item) }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.carnet_identidad + ' ' + props.item.departamento_extension.sigla }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded">
                      <span v-if="props.item.carnet_productor.length > 0">{{ props.item.carnet_productor[0].carnet }} </span>
                      <span v-else> - </span>
                    </td>                    
                  </tr>
                </template>
                <template v-slot:expand="props">
                  <v-card flat color="blue-grey lighten-5">
                    <v-card-text >
                      <v-layout row wrap>
                        <v-flex xs3>
                          <v-avatar size="150px">
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
            </v-stepper-content>
            <v-stepper-content step="2">
              <v-form ref="form2" v-model="valid" lazy-validation>
                <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-card-text>     
                <v-autocomplete  
                  v-model="selectedItem.region_id"
                  :items="regiones" 
                  item-text="nombre"
                  item-value="id"
                  label="Region"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-autocomplete>   

                <v-text-field 
                  v-model="selectedItem.codigo_catastral"
                  label="Codigo"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 5) || 'No mayor a 5 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>

                <v-select  
                  v-model="selectedItem.motivo_actualizacion_id"
                  :items="motivos"
                  item-text="nombre"
                  item-value="id"
                  label="Motivo de Actualización"
                ></v-select>

                <v-text-field 
                  v-model="selectedItem.latitud"
                  label="Latitud"
                ></v-text-field>
                
                <v-text-field 
                  v-model="selectedItem.longitud"
                  label="Longitud"            
                ></v-text-field>  
                
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card-text>                
                <v-textarea
                  v-model="selectedItem.descripcion"
                  label="Descripción"
                ></v-textarea>  
                    <template>
                      <vue-clip 
                          :options="options" 
                          :on-complete="complete">
                          <template slot="clip-uploader-action">                        
                          <div class="dz-message v-btn v-btn--small theme--light success">Subir archivo PDF</div>                        
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
                    <v-text-field
                      v-model="valArchivo"
                      readonly box
                      label="Archivo PDF"
                    ></v-text-field>              
              </v-card-text>
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
        <v-btn v-if="step!=2" color="info" small @click.native="nextStep()">Siguiente</v-btn>
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

Vue.use(VueClip)

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
      pais_nacimiento: null,
      menu: null,
      errorCarnet: null,
      errorEmail: null,
      birth_date: null,
      birth_date_formatted: null,
      personas: [],
      persona: [],
      regiones: [],
      motivos: [],
      userValid: true,
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
          ],
      tiposPersona: [],  
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
      fileAdjunto: null,
      valArchivo: null,
      idOld: null,   
    };
  },
  created() {    
  }, 

  mounted() {
    this.bus.$on("openDialog", item => {   
      this.dialog = true      
      this.selectedIndex = item     
      this.getPersona() 
    });
    this.getTiposPersona()
    this.getPersonas()
    this.getRegiones()
    this.getMotivosActualizacion()

  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Registro de Autorización de Renovación'
    },
  },
  watch: {
    birth_date(val){
      this.selectedItem.fecha_nacimiento = val
      this.birth_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },     
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.pais_nacimiento = null      
      this.img = null
      this.step = 1
      userValid: true,
      this.persona = []
      this.$refs.form1.reset()
      this.bus.$emit("closeDialog")
    },
    async save() {
      try {
        if (this.$refs.form2.validate()) {
       
          if (this.selectedIndex != -1) {                      
            await axios.put("api/parcela/"+this.selectedItem.id, this.selectedItem)
            this.selectedItem.verificacion_destruccion=true;  
            await axios.post("api/autorizacionrenovacion/", this.selectedItem)
          } else {           
            await axios.post("api/parcela", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }        
      } catch(e) {
        console.log(e)
      }
    },
  
    async getTiposPersona() {
      let res = await axios.get("api/tipo_persona")
      this.tiposPersona = res.data
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

    async getPersonas() {
      let res = await axios.get("api/persona")
      this.personas = res.data
    },

    async getRegiones() {
      let res = await axios.get("api/regional")
      this.regiones = res.data
    },

    async getMotivosActualizacion() {
      let res = await axios.get("api/motivoactualizacion")
      this.motivos = res.data
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

    complete (file, status, xhr) {
      this.fileAdjunto = xhr.response
      this.valArchivo = "Cargado correctamente."
    },

  },
};
</script>