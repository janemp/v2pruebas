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
            <v-stepper-step step="1" :complete="step > 1">Resolución</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Incineración</v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-form ref="form1" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedItem.resolucion_administrativa_id"
                  :items="resolucionesAdministrativas"
                  item-text="resolucion"
                  item-value="id"
                  label="Resolución administrativa"
                  :rules="[v => !!v || 'Requerido']"
                  :error-messages="errorResolucion"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  @change="getResolucionAdministrativa()"
                  >
                </v-autocomplete>
              </v-form>
              <v-data-table
                :headers="headers"
                :items="resolucionAdministrativa"
                hide-actions
                >
                <template slot="items" slot-scope="props">
                  <tr>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.resolucion }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ (props.item.persona_id)?fullName(props.item.persona):'-' }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ (props.item.persona_id)?(props.item.persona.carnet_identidad+' '+props.item.persona.departamento_extension.sigla):'-' }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.tipo_resolucion.nombre }} </td>
                    
                  </tr>
                </template>
                <template v-slot:expand="props">
                  <v-card flat color="blue-grey lighten-5">
                    <v-card-text >
                      <v-layout row wrap>
                        <v-flex xs3>
                          <v-avatar
                            size="150px"
                            v-if="props.item.persona_id">
                            <img v-bind:src="props.item.persona.fotografia">
                          </v-avatar>
                          <v-avatar
                            size="150px" 
                            v-else>
                            <img v-bind:src="'img/user.png'">
                          </v-avatar>
                        </v-flex>
                        <v-flex xs9>
                          <table>                                      
                            <tr>
                              <th class="text-xs-left">Fecha resolución: </th>
                              <td class="text-xs-left">
                                {{ formatDate(props.item.fecha_resolucion) }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Número de taques: </th>
                              <td class="text-xs-left">
                                {{ props.item.numero_taques }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Observaciones: </th>
                              <td class="text-xs-left">
                                {{ props.item.observaciones }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Documento adjunto: </th>
                              <td class="text-xs-left">
                                <v-btn icon :href="props.item.adjunto" target="_blank">
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
            </v-stepper-content>
            <v-stepper-content step="2">
              <v-form ref="form2" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedItem.motivo_incineracion_id"
                  :items="motivosIncineracion"
                  item-text="nombre"
                  item-value="id"
                  label="Motivo de la incineración"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  >
                </v-autocomplete>
                <v-text-field
                  v-model="selectedItem.numero_taques"
                  label="Cantidad de taques"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => (v && v <= 999) || 'No mayor a 999']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.lugar"
                  label="Lugar de incineración"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
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
                    v-model="date_formatted"
                    label="Plazo para la incineración"
                    prepend-icon="event"
                    readonly
                    slot="activator"
                    :rules="[v => !!v || 'Requerido']"
                    hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                    class="mr-3"
                  ></v-text-field>
                  <v-date-picker v-model="date" no-title @input="menu = false"></v-date-picker>
                </v-menu>
                <v-combobox
                  v-model="selectedItem.responsables"
                  :items="responsables"
                  item-text="nombre"
                  :return-object="false"
                  label="Responsable" 
                  :rules="[v => !!v || 'Requerido']"
                  multiple chips
                ></v-combobox>
                <v-text-field
                  v-model="selectedItem.autorizacion"
                  label="Autorización"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 64) || 'No mayor a 64 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.transporte"
                  label="Medio de transporte"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 64) || 'No mayor a 64 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.destino"
                  label="Destino de las cenizas"
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
      selectedSalida: {},
      resolucionesAdministrativas: [],
      resolucionAdministrativa: [],
      tipo: null,
      errorResolucion: null,
      headers: [
            {
              text: "Resolución administrativa",
              value: "resolucion",
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
              text: "Tipo",
              value: "persona.carnet_productor[0].carnet",
              align: "center"
            }
          ],
      ingreso: 0,
      salida: 0,
      saldo: 0,
      menu: null,
      date_formatted: null,
      date: null,
      responsables: [],
      motivosIncineracion: [],
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.date_formatted = this.$moment(item.plazo).format("DD/MM/YYYY")
      let re = []
      for(let ree of item.responsables) {
        re.push(ree.nombre)
      }
      this.selectedItem.responsables = re
    });
    this.getResolucionesAdministrativas()
    this.getResponsables()
    this.getMotivosIncineracion()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar'
    },
  },
  watch: {
    date(val){
      this.selectedItem.plazo = val
      this.date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}      
      this.step = 1

      this.retencion = []
      this.$refs.form1.reset()
      this.$refs.form2.reset()
      this.bus.$emit("closeDialog")
    },
    async save() {
      try {
        if (this.$refs.form2.validate()) {
          this.selectedItem.user_id = this.$store.getters["auth/user"].id
          let ree = []
          for(let responsable of this.selectedItem.responsables) {
            let getResponsable = await axios.get("api/responsable/fill/" + JSON.stringify({'nombre': responsable}))          
            if(getResponsable.data.length > 0) {
              ree.push(getResponsable.data[0])
            } else {
              let setResponsable = await axios.post("api/responsable", {nombre: responsable})
              ree.push(setResponsable.data)
            }
          }
          this.selectedItem.responsables = []
          this.selectedItem.responsables = ree          
          if (this.selectedIndex != -1) {
            await axios.put("api/incineracion/"+this.selectedItem.id, this.selectedItem)
          } else {
            let aux = await axios.post("api/incineracion", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getResolucionesAdministrativas() {
      let res = await axios.get("api/resolucion_administrativa")
      this.resolucionesAdministrativas = res.data
    },
    async getResolucionAdministrativa(){
      let res = await axios.get("api/resolucion_administrativa/fill/" + JSON.stringify({id: this.selectedItem.resolucion_administrativa_id}))
      this.resolucionAdministrativa = res.data
      this.selectedItem.numero_taques = res.data[0].numero_taques
      if(res.data[0].tipo_resolucion_id == 7){
        this.errorResolucion = null
      } else {
        this.errorResolucion = 'No es resolución de incineración.'
      }
      this.tipo = res.data[0].tipo_resolucion_id
    },
    async getResponsables() {
      let res = await axios.get("api/responsable")
      this.responsables = res.data
    },
    async getMotivosIncineracion() {
      let res = await axios.get("api/motivo_incineracion")
      this.motivosIncineracion = res.data
    },
    nextStep() {
      switch (this.step) {
        case 1:
          if(this.$refs.form1.validate() && this.errorRetencion==null) {           
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
      return [item.nombre, item.primer_nombre, item.segundo_nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },    
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
    formatDateTime(date){
      return this.$moment(date).format("DD/MM/YYYY HH:mm")
    }
  },  
};
</script>