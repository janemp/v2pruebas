<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Donaciones</v-toolbar-title>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item.beneficiario_donacion.persona) }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.beneficiario_donacion.persona.carnet_identidad }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
              <v-chip v-if="props.item.entregado" color="primary" text-color="white">Entregado</v-chip>
              <v-chip v-else color="secondary" text-color="white">Pendiente</v-chip>
            </td>
            <td class="justify-center layout">
              <!-- <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="printItem(props.item)">
                  <v-icon>print</v-icon>
                </v-btn>
                <span>Imprimir</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {'cambia': false})">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>                       -->
              <v-tooltip top>
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
              <table>  
                <tr>
                  <th class="text-xs-left">Entidad: </th>
                  <td class="text-xs-left"> {{ props.item.beneficiario_donacion.nombre }} </td>
                </tr>
                <tr>
                  <th class="text-xs-left">Cantidad de taques: </th>
                  <td class="text-xs-left"> {{ props.item.numero_taques }} </td>
                </tr>                  
                <tr>
                  <th class="text-xs-left">Domicilio de representante legal: </th>
                  <td class="text-xs-left"> {{ fullName(props.item.beneficiario_donacion.persona) }} </td>
                </tr>
                <tr>
                  <th class="text-xs-left">Destino: </th>
                  <td class="text-xs-left"> {{ props.item.destino }} </td>
                </tr>
                <tr>
                  <th class="text-xs-left">Observaciones: </th>
                  <td class="text-xs-left"> {{ props.item.observaciones }} </td>
                </tr>
              </table>
            </v-card-text>
          </v-card>
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
        text: "Codigo Resolución",
        value: "resolucion_administrativa",
        align: "center"
      },
      {
        text: "Nombre de representante legal",
        value: "segundo_apellido",
        align: "center"
      },
      {
        text: "Carnet de identidad",
        value: "carnet_identidad",
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
    table: [],
    search: "",
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
        let res = await axios.get("api/donacion")
        this.table = res.data
        console.log(this.table)
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
      console.log(item)
      this.bus.$emit("openDialogRemove", `api/donacion/${item.id}`);      
    },
    fullName(item) {
      return [item.primer_nombre, item.segundo_nombre, item.primer_apellido, item.segundo_apellido].join(" ")
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
