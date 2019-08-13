<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Áreas y puestos de venta</v-toolbar-title>
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
        <Cambio :bus="bus"/>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.puesto_venta.municipio.nombre }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.puesto_venta.mercado }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.puesto_venta.zona  }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.puesto_venta.direccion }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
              <v-chip class="white--text" v-if="props.item.puesto_venta.cambiado == false" color="success">Activo</v-chip>
              <v-chip class="white--text" v-if="props.item.puesto_venta.cambiado == true" color="error">Cambiado</v-chip>
            </td>
            <td class="justify-center layout">
              <span v-if="!props.item.puesto_venta.cambiado">
                <!-- <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="printItem(props.item)">
                    <v-icon>print</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
                </v-tooltip> -->
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {'cambia': false})">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="cambiar(props.item)">
                    <v-icon>refresh</v-icon>
                  </v-btn>
                  <span>Cambiar</span>
                </v-tooltip>              
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                    <v-icon>delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
              </span>
            </td>
          </tr>
        </template>
        <template v-slot:expand="props">
          <v-card flat color="blue-grey lighten-5">
            <v-card-text >
              <v-layout row wrap>
                <v-flex xs2>
                  <v-avatar
                    size="150px">
                    <img v-bind:src="props.item.persona.fotografia">
                  </v-avatar>
                </v-flex>
                <v-flex xs10>
                  <table>                    
                    <tr>
                      <th class="text-xs-left">Nombre del propietario: </th>
                      <td class="text-xs-left"> {{ props.item.persona.nombre }} </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Carnet del propietario: </th>
                      <td class="text-xs-left"> {{ props.item.persona.carnet_identidad }} </td>
                    </tr>
                    <tr v-if="props.item.puesto_venta.cambiado">
                      <td colspan="2"><hr></td>
                    </tr>
                    <tr v-if="props.item.puesto_venta.cambiado">
                      <th class="text-xs-left">Motivo de cambio: </th>
                      <td class="text-xs-left"> {{ props.item.autorizacion_cambio_puesto_venta[0].motivo_cambio }} </td>
                    </tr>
                    <tr v-if="props.item.puesto_venta.cambiado">
                      <th class="text-xs-left">Documento de cambio: </th>
                      <td class="text-xs-left"><a :href="props.item.autorizacion_cambio_puesto_venta[0].adjunto" target="blank"><v-icon color="red">picture_as_pdf</v-icon></a></td>
                    </tr>
                  </table>
                </v-flex>
              </v-layout>
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
import Cambio from './Cambio'

export default {
  components: {
    RemoveItem,
    Form,
    Cambio
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Municipio",
        value: "puesto_venta.municipio.nombre",
        align: "center"
      },
      {
        text: "Mercado",
        value: "puesto_venta.mercado",
        align: "center"
      },
      {
        text: "Zona",
        value: "puesto_venta.zona",
        align: "center"
      },
      {
        text: "Dirección",
        value: "puesto_venta.direccion",
        align: "center"
      },   
      {
        text: "Estado",
        value: 'puesto_venta.cambiado',
        align: "center"
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
    this.bus.$on("closeDialogCambiar", () => {
      this.getTable();
    });
  },
  methods: {
    async getTable() {
      try {
        let res = await axios.get("api/persona_puesto_venta")
        console.log(res.data)
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    cambiar(item){
      this.bus.$emit('openDialogCambiar', item)
    },
    editItem(item, mode) {
      this.bus.$emit("openDialog", Object.assign(item, mode));
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/puesto_venta/${item.id}`);      
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