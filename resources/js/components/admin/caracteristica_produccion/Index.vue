<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Parcelas</v-toolbar-title>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ formatDate(props.item.fecha_parcela) }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.codigo_catastral }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.comunidad.nombre }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.latitud }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.longitud }} </td>
            <td class="justify-center layout">              
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item)">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
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
              <v-layout row wrap>
                <v-flex>
                  <table class="v-table theme--light">
                    <tr>
                      <th class="text-xs-left">Descripción: </th>
                      <td class="text-xs-left">{{ props.item.descripcion }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Hectáreas: </th>
                      <td class="text-xs-left">{{ props.item.hectareas }}</td>
                    </tr>                  
                    <tr v-if="props.item.motivo_actualizacion != null">
                      <th class="text-xs-left">Motivo de actualización: </th>
                      <td class="text-xs-left">{{ props.item.motivo_actualizacion.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Código catastral: </th>
                      <td class="text-xs-left">{{ props.item.codigo_catastral }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Región: </th>
                      <td class="text-xs-left">{{ props.item.region.nombre }}</td>
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
import Vue from "vue";
import axios from 'axios'
import RemoveItem from "../RemoveItem";
import Form from "./Form";

export default {
  components: {
    RemoveItem,
    Form,
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Fecha parcela",
        value: "fecha_parcela",
        align: "center"
      },
      {
        text: "Código catastral",
        value: "codigo_catastral",
        align: "center"
      },
      {
        text: "Comunidad",
        value: "comunidad.nombre",
        align: "center"
      },
      {
        text: "Latitud",
        value: "latitud",
        align: "center"
      },
      {
        text: "Longitud",
        value: "longitud",
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
  },
  methods: {
    async getTable() {
      try {
        let res = await axios.get("api/parcela")
        this.table = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/parcela/${item.id}`);      
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY")
    }
  }
};
</script>
