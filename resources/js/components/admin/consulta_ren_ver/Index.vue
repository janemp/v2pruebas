<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Consulta de Renovaciones y Verficiación de Parcelas</v-toolbar-title>
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
            <td class="text-xs-left"> {{ props.item.comunidad.nombre }} </td>
            <td class="text-xs-left"> {{ props.item.hectareas }} </td>
            <td class="text-xs-center"> {{ props.item.latitud }} </td>
            <td class="text-xs-center"> {{ props.item.longitud }} </td>
            <td class="justify-center layout"> 
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="openItem(props.item)">
                  <v-icon>assignment</v-icon>
                </v-btn>
                <span>Verificación de Renovación</span>
              </v-tooltip>
            </td>
          </tr>
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
import Form from "./Form"

export default {
  components: {
    Form,
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Comunidad",
        value: "comunidad.nombre",
        align: "center"
      },
      {
        text: "Hectáreas",
        value: "hectareas",
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
        sortable: false,
        width: "270"
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
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    openItem(item) {
      this.bus.$emit("openDialogForm", item);
    },
  }
};
</script>
