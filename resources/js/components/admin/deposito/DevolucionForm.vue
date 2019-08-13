<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">
          Nuevo
          </v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
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
            
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>        
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'

export default {
  components: {},  
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
      //
      beneficiarios: [],
      hojasRuta: []
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogDevolucionForm", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.selectedItem.deposito_id = item.id
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
      this.resolucionAdministrativa = []
      this.errorResolucion = null
      this.$refs.form1.reset()
      this.bus.$emit("closeDialogDevolucion")
    },
    async save() {
      try {
        if (this.$refs.form1.validate()) {         
          if (this.selectedIndex != -1) {
            this.selectedItem.user_id = this.$store.getters["auth/user"].id            
            await axios.post("api/devolucion", this.selectedItem)
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
      if(res.data[0].tipo_resolucion_id == 5){
        this.errorResolucion = null
      } else {
        this.errorResolucion = 'No es resolución de devolucion.'
      }
      this.tipo = res.data[0].tipo_resolucion_id
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