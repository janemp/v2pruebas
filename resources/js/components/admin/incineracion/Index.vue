<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Incineraciones</v-toolbar-title>
        <v-spacer></v-spacer>   
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-toolbar-title>
          <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
              width="20px"
            ></v-text-field>
        </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>        
        <Form :bus="bus"/>
        <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
        :headers="headers"
        :items="table"
        :search="search"
        :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.resolucion_administrativa.resolucion }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item.resolucion_administrativa.persona) }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.resolucion_administrativa.persona.carnet_identidad + ' ' + props.item.resolucion_administrativa.persona.departamento_extension.sigla }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.numero_taques  }} </td>
            <td class="justify-center layout">
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item)">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>                         
                <v-tooltip top v-if="$acl.check('admin')">
                  <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                    <v-icon>delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
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
                    <img v-bind:src="props.item.resolucion_administrativa.persona.fotografia">
                  </v-avatar>
                </v-flex>
                <v-flex xs9>
                  <table class="v-table theme--light">  
                    <tr>
                      <th class="text-xs-left">Tipo de resolución: </th>
                      <td class="text-xs-left">{{ props.item.resolucion_administrativa.tipo_resolucion.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Motivo de incineración: </th>
                      <td class="text-xs-left">{{ props.item.motivo_incineracion.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Lugar: </th>
                      <td class="text-xs-left">{{ props.item.lugar }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Plazo: </th>
                      <td class="text-xs-left">{{ formatDate(props.item.plazo) }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Destino: </th>
                      <td class="text-xs-left">{{ props.item.destino }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Autorización: </th>
                      <td class="text-xs-left">{{ props.item.autorizacion }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Transporte: </th>
                      <td class="text-xs-left">{{ props.item.transporte }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Responsablese: </th>
                      <td class="text-xs-left">
                        <div v-for="(responsable, index) in props.item.responsables" :key="index">
                          {{ responsable.nombre }}
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Observaciones: </th>
                      <td class="text-xs-left">{{ props.item.observaciones }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Documento adjunto: </th>
                      <td class="text-xs-left">
                        <v-btn icon :href="props.item.resolucion_administrativa.adjunto" target="_blank">
                          <v-icon color="red">picture_as_pdf</v-icon>
                        </v-btn>
                      </td>
                    </tr>
                    <!-- <tr>
                      <th class="text-xs-left">Documento adjunto: </th>
                      <td class="text-xs-left">
                        <v-btn icon :href="props.item.resolucion_administrativa.adjunto" target="_blank">
                          <v-icon color="red">picture_as_pdf</v-icon>
                        </v-btn>
                      </td>
                    </tr> -->
                  </table>
                </v-flex>
              </v-layout>
            </v-card-text>
          </v-card>
        </template>
        <template v-slot:footer>
          <td colspan="3">
            <strong>Totales </strong> 
          </td>
          <th class="text-xs-center">
            {{ totalTaques }} 
          </th>
        </template>   
        <v-alert slot="no-results" :value="true" color="error" icon="fa fa-times">
          Tu Busqueda de "{{ search }}" no encontró resultados.
        </v-alert>
        <template slot="no-data">
          <v-alert slot="no-results" :value="true" color="info" icon="fa fa-times">
            Sin resultados.
          </v-alert>
        </template>
    </v-data-table>
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue"
import axios from 'axios'
import RemoveItem from "../RemoveItem"
import Form from "./Form"
import print from 'print-js'

export default {
  components: {
    RemoveItem,
    Form,
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Resolución administrativa",
        value: "resolucion_administrativa.resolucion",
        align: "center"
      },
      {
        text: "Responsable",
        value: "resolucion_administrativa.persona.primer_apellido",
        align: "center"
      },
      {
        text: "C.I.",
        value: "resolucion_administrativa.persona.carnet_identidad",
        align: "center"
      },
      {
        text: "Numero de taques",
        align: "center",
        sortable: false
      },            
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    table: [],
    search: "",
    totalTaques: 0,
  }),
  computed: {},
  mounted() {
    this.getTable();
    this.bus.$on("closeDialog", () => {
      this.getTable();
    });
  },
  methods: {
    async getTable() {
      try {
        let res = await axios.get("api/incineracion")
        this.table = res.data
        this.totalTaques = 0
        res.data.forEach(function(item, index, array) {
          this.totalTaques = this.totalTaques + item.numero_taques
        }, this);
      } catch (e) {
        console.log(e)
      }
    },
    async printItem(item) {
      try {        
        let res = await axios({
          method: "GET",
          url: "api/puesto_venta/print/" + item.id,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {type: "application/pdf"});
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {      
      this.bus.$emit("openDialogRemove", `api/incineracion/${item.id}`);
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
  }
};
</script>
