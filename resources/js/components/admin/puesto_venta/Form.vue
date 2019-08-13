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
            <v-stepper-step step="2" :complete="step > 2">Puesto de venta</v-stepper-step>
            <!-- <v-divider v-if="cambia"></v-divider>
            <v-stepper-step step="3" :complete="step > 3" v-if="cambia">Cambio</v-stepper-step> -->
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-form ref="form1" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedItem.persona_id"
                  :items="comercializadores"
                  item-text="carnet_identidad"
                  item-value="id"
                  label="Carnet de identidad de comercializador"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  :error-messages="errorPersona"
                  @change="getComercializador()"
                  >
                </v-autocomplete>
              </v-form>
              <v-data-table
                :headers="headers"
                :items="comercializador"
                hide-actions
                >
                <template slot="items" slot-scope="props">
                  <tr>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item) }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.carnet_identidad + ' ' + props.item.departamento_extension.sigla }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.telefono }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.email }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
                      
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
                          <table>
                            <tr>
                              <th class="text-xs-left">Estado civil: </th>
                              <td class="text-xs-left">{{ props.item.estado_civil }}</td>
                            </tr>                  
                            <tr>
                              <th class="text-xs-left">Genero: </th>
                              <td class="text-xs-left">{{ (props.item.genero=='M')?'Masculino':'Femenino' }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Fecha de nacimiento: </th>
                              <td class="text-xs-left">{{ formatDate(props.item.fecha_nacimiento) }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Pais de nacimiento: </th>
                              <td class="text-xs-left">{{ props.item.pais_nacimiento.nombre }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Departamento de nacimiento: </th>
                              <td class="text-xs-left">{{ props.item.departamento_nacimiento.nombre }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Provincia de nacimiento: </th>
                              <td class="text-xs-left">{{ props.item.provincia_nacimiento.nombre }}</td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Domicilio: </th>
                              <td class="text-xs-left">{{ props.item.domicilio }}</td>
                            </tr>                            
                            <tr v-if="props.item.sustituido == true">
                              <th colspan="2"><hr></th>
                            </tr>
                            <tr v-if="props.item.sustituido == true">
                              <th class="text-xs-left">Sustituyo a: </th>
                              <td class="text-xs-left">{{ fullName(props.item.comercializador) }} </td>
                            </tr>
                            <tr v-if="props.item.sustituido == true">
                              <th class="text-xs-left">Motivo de sustitución: </th>
                              <td class="text-xs-left">{{ props.item.motivo_sustitucion }} </td>
                            </tr>
                            <tr v-if="props.item.sustituido == true">
                              <th class="text-xs-left">Documento de sustitución: </th>
                              <td class="text-xs-left"><a :href="'uploads/' + props.item.adjuntos_sustitucion" target="blank"><v-icon color="red">picture_as_pdf</v-icon></a></td>
                            </tr>
                            <tr v-if="props.item.cesado == true">
                              <th colspan="2"><hr></th>
                            </tr>
                            <tr v-if="props.item.cesado == true">
                              <th class="text-xs-left">Motivo de cesación: </th>
                              <td class="text-xs-left">{{ props.item.motivo_cesacion }} </td>
                            </tr>
                            <tr v-if="props.item.cesado == true">
                              <th class="text-xs-left">Documento de cesación: </th>
                              <td class="text-xs-left"><a :href="'uploads/' + props.item.adjuntos_cesacion" target="blank"><v-icon color="red">picture_as_pdf</v-icon></a></td>
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
                <v-autocomplete
                :items="municipios"
                v-model="selectedPuesto.municipio_id"
                item-value="id"
                item-text="nombre"
                label="Municipio"
                :rules="[v => !!v || 'Requerido']"
                hint="<span class='blue--text'>*Requerido</span>" 
                persistent-hint>
                </v-autocomplete>          
                <v-text-field
                  v-model="selectedPuesto.zona"
                  label="Zona"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 64) || 'No mayor a 64 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedPuesto.mercado"
                  label="Mercado"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 64) || 'No mayor a 64 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedPuesto.direccion"
                  label="Dirección"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 64) || 'No mayor a 64 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
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
        <v-btn v-if="step==2 && cambia==false" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
        <!-- <v-btn v-if="step==3 && cambia==true" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Cambiar</v-btn> -->
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import VueClip from 'vue-clip'

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
      selectedCambia: {},
      selectedPuesto: {},
      cambia: false,
      idOld: null,
      adjuntos: null,
      comercializadores: [],
      comercializador: [],  
      errorPersona: null,    
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
              text: "Telefono",
              value: "telefono",
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
      municipios: []
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.cambia = item.cambia
      this.idOld = item.id
      this.getComercializador()
    });
    this.getComercializadores()
    this.getMunicipios()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar'
    },
  },
  watch: {
    
  },
  methods: {    
    close() {
      this.$refs.form1.reset()
      this.$refs.form2.reset()
      if(this.cambia) {
        this.$refs.form3.reset()
      }  
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.selectedCambia = {} 
      this.cambia = false
      this.idOld = null    
      this.adjuntos = null
      this.step = 1
      this.bus.$emit("closeDialog")
      this.comercializador = []
    },
    async save() {
      try {
        if (this.$refs.form2.validate()) {
          let res = await axios.post('api/puesto_venta', this.selectedPuesto)
          var data = {
            'puesto_venta_id': res.data.id,
            'persona_id': this.comercializador[0].id
          }
          console.log(data)
          await axios.post('api/persona_puesto_venta', data)
          this.$toast.success('Correcto.')
          this.close();
        }
        // if (this.$refs.form3.validate() && this.cambia == true) {
        //   let setPuesto = await axios.post("api/puesto_venta", this.selectedItem)
        //   this.selectedCambia.puesto_venta_id = setPuesto.data.id
        //   this.selectedCambia.cambiado = true
        //   await axios.put("api/puesto_venta/"+this.idOld, this.selectedCambia)
        //   this.$toast.success('Correcto.')
        //   this.close();
        // }
      } catch(e) {
        console.log(e)
      }
    },
    async getComercializadores() {
      var param = {'tipo_persona_id': 4}
      let res = await axios.get("api/persona")
      this.comercializadores = res.data
    },
    async getComercializador() {
      let res = await axios.get("api/persona/fill/" + JSON.stringify({'id': this.selectedItem.persona_id}))
      console.log(res.data[0].tipo_persona_id)
      if (res.data[0].tipo_persona_id != 1) {
        this.comercializador = res.data
        this.errorPersona = null
      } else {
        this.errorPersona = "Tipo de persona no permitido"
      }
    },
    async getMunicipios(){
      let res = await axios.get('api/municipio')
      this.municipios = res.data
    },
    // UPLOAD
    complete (file, status, xhr) {
      this.selectedCambia.adjuntos_cambio = xhr.response
      this.adjuntos = 'Cargado correctamente.'
    },
    nextStep() {
      switch (this.step) {
        case 1:
          if(this.$refs.form1.validate()) {           
            this.step = 2            
          }
          break
        // case 2:
        //   if(this.$refs.form2.validate() && this.cambia) {           
        //     this.step = 3           
        //   }
        //   break
      }
    },
    previousStep() {
      switch (this.step) { 
        // case 3:          
        //   this.step = 2         
        //   break
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