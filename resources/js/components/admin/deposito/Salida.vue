<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Salida</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-stepper v-model="step" vertical>
          <v-stepper-header>
            <v-stepper-step step="1" :complete="step > 1">Resolución administrativa</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2" v-if="mode=='donar'||mode=='incinerar'">Salida</v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-form ref="form1" v-model="valid" lazy-validation>
                <v-autocomplete                      
                  v-model="selectedSalida.resolucion_administrativa_id"
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
            <v-stepper-content step="2" v-if="mode=='donar'||mode=='incinerar'">
              <v-form ref="form2" v-model="valid" lazy-validation>

              </v-form>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <span v-if="mode=='donar'||mode=='incinerar'">
          <v-btn v-if="step!=1" color="info" small @click.native="previousStep()">Anterior</v-btn>
          <v-btn color="info"  small :disabled="!valid" @click.native="nextStep()">Siguiente</v-btn>
        </span>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn v-if="step==1&&mode=='decomisar'||mode=='devolver'" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
        <v-btn v-if="step==2&&mode=='donar'||mode=='incinerar'" color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import { constants } from 'crypto';

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
      mode: null,
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
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogExit", item => {
      this.mode = item.mode
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.selectedSalida.deposito_id = this.selectedItem.id
      this.getResolucionesAdministrativas()
    });
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
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.selectedSalida = {}
      this.tipo = null
      this.resolucionAdministrativa = []
      this.errorResolucion = null
      this.ingreso = 0
      this.salida = 0
      this.saldo = 0
      this.$refs.form1.reset()
      this.bus.$emit("closeDialog")
    },
    async save() {
      try {
        if (this.$refs.form1.validate()) {         
          if (this.selectedIndex != -1) {
            this.selectedSalida.user_id = this.$store.getters["auth/user"].id
            this.selectedItem.user_id = this.$store.getters["auth/user"].id
            this.selectedItem.numero_taques_salida = this.salida
            this.selectedItem.numero_taques_saldo = this.saldo
            if(this.tipo == 4){ //decomiso
              let decomiso = await axios.post("api/decomiso", this.selectedSalida)
              await axios.put("api/deposito/"+this.selectedItem.id, this.selectedItem)
            }
            if(this.tipo == 5){ //devolucion
              let devolucion = await axios.post("api/devolucion", this.selectedSalida)
              await axios.put("api/deposito/"+this.selectedItem.id, this.selectedItem)
              await axios.put("api/guia_internacion/"+this.selectedItem.retencion.guia_internacion_id, {retenido: false})
            }
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
      this.ingreso = 0
      this.salida = 0
      this.saldo = 0
      let res = await axios.get("api/resolucion_administrativa/fill/" + JSON.stringify({id: this.selectedSalida.resolucion_administrativa_id}))
      this.resolucionAdministrativa = res.data
      this.salida = this.selectedItem.numero_taques_salida + res.data[0].numero_taques
      this.saldo = this.selectedItem.numero_taques_saldo - res.data[0].numero_taques
      if(this.mode == 'decomisar'){
        this.selectedSalida.numero_taques_ingreso = res.data[0].numero_taques
        this.selectedSalida.numero_taques_saldo = res.data[0].numero_taques        
        if(res.data[0].tipo_resolucion_id == 4){
          this.errorResolucion = null
        } else {
          this.errorResolucion = 'No es resolución de decomiso.'
        }
      }
      if(this.mode == 'devolver'){        
        this.selectedSalida.numero_taques = res.data[0].numero_taques
        if(res.data[0].tipo_resolucion_id == 5){
          this.errorResolucion = null
        } else {
          this.errorResolucion = 'No es resolución de devolución.'
        }
      }
      if(this.mode == 'donar'){
        if(res.data[0].tipo_resolucion_id == 6){
          this.errorResolucion = null
        } else {
          this.errorResolucion = 'No es resolución de donación.'
        }
      }
      this.tipo = res.data[0].tipo_resolucion_id
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