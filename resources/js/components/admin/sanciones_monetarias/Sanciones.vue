<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
  <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Sanciones</v-toolbar-title>         
        <Form :bus="bus"/>
        <RemoveItem :bus="bus"/>
        <v-spacer></v-spacer>   
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
      </v-toolbar>
     
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="table"
          :search="search"
          :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
          disable-initial-sort
          class="elevation-1">
          <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center"> {{ props.item.infraccion.nombre }} </td>
            <td class="text-xs-center"> {{ props.item.codigo }} </td>
            <td class="text-xs-center"> {{ props.item.nombre }} </td>
            <td class="text-xs-center"> {{ props.item.descripcion }} </td>
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
          <v-alert slot="no-results" :value="true" color="error" icon="fa fa-times">
            Tu Busqueda de "{{ search }}" no encontró resultados.
          </v-alert>
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
      </v-card-actions>
  </v-card>
  </v-dialog>
</template>

<script>
import Vue from "vue";
import axios from 'axios'
import RemoveItem from "../RemoveItem";
import Form from "./Form";

export default {
  components: {
    RemoveItem,
    Form,
  },
  props: ["item", "bus"], 
  //data: () => ({
    //bus: new Vue(),
  data(){
    return {
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      table: [],
      search: null,
      headers: [
        {
          text: "Infracción",
          value: "infraccion.nombre",
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
          text: "Opciones",
          align: "center",
          sortable: false
        }
      ],
      table: [],
      search: "",
    }
  },
  //}),
  computed: {},
  mounted() {
    //this.getTable();
    this.bus.$on("openDialogSancion", item => {
      this.dialog = true
      this.selectedItem = item
      this.getTable();
    });
      this.bus.$on("closeDialogForm", () => {
      this.getTable()
    }); 
  },
  methods: {
     close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}  
      this.table = []
      this.bus.$emit("closeDialog")
    },
    async getTable() {
      try {
        let res = await axios.get("api/sancion")
        this.table = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialogForm", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/sancion/${item.id}`);      
    },
  }
};
</script>
