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
            <v-stepper-step step="1" :complete="step > 1">Retención</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2" :complete="step > 2">Deposito</v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-form ref="form1" v-model="valid" lazy-validation>
                <v-layout row wrap>
                  <v-flex md6 >
                    <p class="error">{{ error }}</p>
                    <p class="decode-result"><b>Código QR.</b></p>
                    <qrcode-stream v-if="dialog" @decode="onDecode" @init="onInit" class="mr-4"/>
                  </v-flex>
                  <v-flex md6>
                    <v-text-field
                      v-model="valQr"
                      :rules="[v => !!v || 'Requerido']"
                      hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                      readonly
                      box
                      :error-messages="errorQr"
                      class="mt-5"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-form>
              <v-data-table
                :headers="headers"
                :items="retencion"
                :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
                disable-initial-sort
                class="elevation-1">
                <template slot="items" slot-scope="props">
                  <tr>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.correlativo }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.inicial_embalaje }} </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded">
                      <v-chip v-if="props.item.guia_internacion_id" small color="green" text-color="white">Guía de internación</v-chip>
                      <v-chip v-if="props.item.hoja_ruta_id" small color="green" text-color="white">Hoja de ruta</v-chip>
                      <v-chip v-if="props.item.guia_internacion_id==null&&props.item.hoja_ruta_id==null" small color="grey" text-color="white">Sin documentos</v-chip>
                    </td>
                    <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ formatDate(props.item.created_at) }} </td>
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
                          <table class="v-table theme--light">
                             <tr>
                              <th class="text-xs-left">Nro. documento: </th>
                              <td class="text-xs-left"> 
                                <span v-if="props.item.guia_internacion_id">                              
                                {{ props.item.guia_internacion.correlativo }}
                                </span>
                                <span v-if="props.item.hoja_ruta_id">
                                {{ props.item.hoja_ruta.id_hoja_ruta }}
                                </span>
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Nombres y apellidos: </th>
                              <td class="text-xs-left">
                                <span v-if="props.item.persona_id">
                                {{ fullName(props.item.persona) }}
                                </span>                                
                                <span v-else>
                                {{ fullName(props.item) }}
                                </span>
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Cedula identidad: </th>
                              <td class="text-xs-left">
                                <span v-if="props.item.persona_id">
                                {{ props.item.persona.carnet_identidad+' '+props.item.persona.departamento_extension.sigla }}
                                </span>                                
                                <span v-else>
                                {{ props.item.carnet_identidad }}
                                </span>
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Iniciales de embalaje: </th>
                              <td class="text-xs-left">
                                {{ props.item.inicial_embalaje }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Causas de retención: </th>
                              <td class="text-xs-left">
                                <v-list>
                                  <v-list-tile v-for="(causa, index) in props.item.causas_retencion" :key="index">
                                    <v-list-tile-content>
                                      <v-list-tile-title class="caption">
                                      {{ causa.nombre }}
                                      </v-list-tile-title>
                                    </v-list-tile-content>
                                  </v-list-tile>
                                </v-list>
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Departamento: </th>
                              <td class="text-xs-left">
                                {{ props.item.departamento.nombre }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Municipio: </th>
                              <td class="text-xs-left">
                                {{ props.item.municipio.nombre }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Localidad: </th>
                              <td class="text-xs-left">
                                {{ props.item.localidad.nombre }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Lugar: </th>
                              <td class="text-xs-left">
                                {{ props.item.lugar }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Descripción: </th>
                              <td class="text-xs-left">
                                {{ props.item.descripcion }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Numero de taques: </th>
                              <td class="text-xs-left">
                                {{ props.item.numero_taques }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Libras: </th>
                              <td class="text-xs-left">
                                {{ props.item.libras }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Terrestre: </th>
                              <td class="text-xs-left">Número de placa:
                                {{ props.item.numero_placa }}
                              </td>
                              <td class="text-xs-left" v-if="props.item.vehiculo_id">Tipo:
                                {{ props.item.vehiculo.nombre }}
                              </td>
                              <td class="text-xs-left" v-if="props.item.marca_id">Marca:
                                {{ props.item.marca.nombre }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Aereo: </th>
                              <td class="text-xs-left">Número de vuelo:
                                {{ props.item.numero_vuelo }}
                              </td>
                              <td class="text-xs-left">Empresa:
                                {{ props.item.empresa_vuelo }}
                              </td>
                              <td class="text-xs-left">Otros:
                                {{ props.item.otros_vuelo }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Fluvial: </th>
                              <td class="text-xs-left">Nombre embarcación:
                                {{ props.item.nombre_embarcacion }}
                              </td>
                              <td class="text-xs-left" v-if="props.item.embarcacion_id">Tipo:
                                {{ props.item.embarcacion.nombre }}
                              </td>
                              <td class="text-xs-left">Número embarcación:
                                {{ props.item.numero_embarcacion }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Responsable: </th>
                              <td class="text-xs-left">
                                {{ props.item.responsable }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Fecha y hora: </th>
                              <td class="text-xs-left">
                                {{ formatDateTime(props.item.created_at) }}
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
                <v-text-field
                  v-model="selectedItem.estado"
                  label="Estado"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-textarea
                  v-model="selectedItem.observaciones"
                  label="Observaciones"
                  rows="2"
                ></v-textarea>
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
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

export default {
  components: {
    QrcodeStream,
    QrcodeDropZone,
    QrcodeCapture,
  },  
  props: ["item", "bus"], 
  data() {
    return {
      step: 1,
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},      
      retenciones: [],
      retencion: [],
      valQr: null,
      errorQr: null,
      headers: [
          {
            text: "Nro. Retención",
            value: "correlativo",
            align: "center"
          },
          {
            text: "Iniciales embalaje",
            value: "primer_apellido",
            align: "center"
          },
          {
            text: "Documento",
            value: "carnet_identidad",
            align: "center"
          },           
          {
            text: "Fecha de retención",
            align: "center",
            sortable: false,
          }
        ],
      qrDecode: [],
      error: null,
      correlativo: 'dsdsd',
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.getRetencion()
      this.valQr = "Correcto."
    });
    this.getRetenciones()
    this.selectedItem.retencion_id = 1
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
          if (this.selectedIndex != -1) {
            await axios.put("api/deposito/"+this.selectedItem.id, this.selectedItem)
          } else {            
            this.selectedItem.tipo_movimiento = 'INGRESO'
            await axios.post("api/deposito", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getRetenciones() {
      let res = await axios.get("api/retencion")      
      this.retenciones = res.data
    },
    async getRetencion() {
      let res = await axios.get("api/retencion/fill/" + JSON.stringify({id: this.selectedItem.retencion_id}))
      this.retencion = res.data
      this.selectedItem.numero_taques = res.data[0].numero_taques
    },
    async getRutas() {
      let res = await axios.get("api/ruta")
      this.rutas = res.data
    },
    async getVehiculos() {
      let res = await axios.get("api/vehiculo")
      this.vehiculos = res.data
    },
    async getMarcas() {
      let res = await axios.get("api/marca")
      this.marcas = res.data
    },    
    async onDecode (result) {
        this.qrDecode = atob(result).split("|")
        this.selectedItem.retencion_id = this.qrDecode[1]
        this.correlativo = this.qrDecode[2]
        if (this.qrDecode[0] == "RETENCION") {
          let res = await axios.get("api/deposito/fill/" + JSON.stringify({retencion_id: this.selectedItem.retencion_id}))
          if(res.data.length > 0) {
            this.errorQr = "Ya existe en depositos."
            this.valQr = null
          } else {
            this.errorQr = null
            this.valQr = "Correcto"
            this.getRetencion()
          }
        } else {
          this.errorQr = "No es una retención."
          this.valQr = null
        } 
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
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
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