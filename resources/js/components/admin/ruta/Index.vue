<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Rutas</v-toolbar-title>
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
        :items="rutas"
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
            <td class="text-xs-center"> {{ props.item.origen.nombre }} </td>
            <td class="text-xs-center"> {{ props.item.destino.nombre }} </td>
            <td class="justify-center layout">              
              <v-tooltip 
                top>
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item)" :disabled="props.item.borrar">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              
              <v-tooltip
                top>
                <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)" :disabled="props.item.borrar">
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
    hojas_control: [],
    control_puesto: [],
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
        text: "Origen",
        value: "origen.nombre",
        align: "center"
      },
      {
        text: "Destino",
        value: "destino.nombre",
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
    rutas: []
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
      let resp = await axios.get("api/ruta")
      this.table = resp.data;
      for(var i=0; i<this.table.length; i++){
        var param = {'ruta_id': this.table[i].id}
        let res = await axios.get('api/hoja_ruta/fill/'+JSON.stringify(param))
        if(res.data.length > 0){
          var param1 = {'hoja_ruta_id': res.data[0].id}
          let res1 = await axios.get('api/control_hoja_ruta/fill/'+JSON.stringify(param1))
          if(res1.data.length > 0){
            this.table[i].borrar = true
          }
          else{
            this.table[i].borrar = false
          }
        }
        else{
          this.table[i].borrar = false
        }
      }
      this.rutas = this.table
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/ruta/${item.id}`);      
    }
  }
};
</script>
