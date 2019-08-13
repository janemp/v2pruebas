<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Puestos de control</v-toolbar-title>
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
            <td class="text-xs-center"> {{ props.item.municipio.nombre }} </td>
            <td class="text-xs-center"> {{ props.item.codigo }} </td>
            <td class="text-xs-center"> {{ props.item.nombre }} </td>
            <td class="text-xs-center"> {{ props.item.descripcion }} </td>
            <td class="text-xs-center"> {{ props.item.latitud }} </td>
            <td class="text-xs-center"> {{ props.item.longitud }} </td>
            <td class="text-xs-center"> {{ props.item.direccion }} </td>
            <td class="justify-center layout">              
              <v-tooltip top>
                <v-btn slot="activator" :disabled="props.item.borrar" flat icon color="indigo" @click="editItem(props.item)">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn slot="activator" :disabled="props.item.borrar" flat icon color="red darken-3" @click="removeItem(props.item)">
                  <v-icon>delete</v-icon>
                </v-btn>
                <span>Eliminar</span>
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
        text: "Municipio",
        value: "municipio.nombre",
        align: "center"
      },
      {
        text: "Codigo",
        value: "codigo",
        align: "center"
      },
      {
        text: "Nombre",
        value: "nombre",
        align: "center"
      },
      {
        text: "Descripción",
        value: "descripcion",
        align: "center"
      },
      {
        text: "Latitud",
        value: "descripcion",
        align: "center"
      },
      {
        text: "Longitud",
        value: "descripcion",
        align: "center"
      },
      {
        text: " Dirección",
        value: "descripcion",
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
        let res = await axios.get("api/puesto_de_control")
        for(var i=0; i<res.data.length; i++){
          if(res.data[i].rutas.length > 0){
            res.data[i].borrar = true
          }
          else{
            res.data[i].borrar = false
          }
        }
        this.table = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/puesto_de_control/${item.id}`);      
    },
  },
};
</script>
