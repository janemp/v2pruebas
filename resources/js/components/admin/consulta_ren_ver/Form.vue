<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
       
  <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Verificación Renovación</v-toolbar-title>                 
        <v-spacer></v-spacer>          
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>            
        <ConfirmItem :bus="bus"/>         
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
            <td class="text-xs-center"> {{ props.item.nombre }} </td>
             <td class="text-xs-center"> {{ props.item.hectareas }} </td>
            <td class="text-xs-center"> {{ props.item.descripcion }} </td>
            <!-- <td class="text-xs-center"> {{ props.item.verificacion_destruccion }} </td>  -->
            
            <td class="justify-center layout">
              <!-- <v-tooltip top v-if="props.item.sustitucion_id==null"> -->
                <v-switch
                  slot="activator"
                  v-model="props.item.verificacion_destruccion"
                  color="success"
                  @change="activeItem(props.item)"
                  class="mt-2"
                ></v-switch>
                <span>{{ (props.item.verificacion_destruccion)? 'Racionalizado':'No Racionalizado' }}</span>
              <!-- </v-tooltip> -->
            </td>
          </tr>         
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
import Form from "./Form";
import ConfirmItem from "../ConfirmItem"

export default {
  components: {
    Form,
    ConfirmItem
  },
  props: ["item", "bus"], 
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
          text: "Comunidad",
          value: "nombre",
          align: "center"
        },
        {
          text: "Hectáreas",
          value: "hectareas",
          align: "center"
        },     
        {
          text: "Descripción",
          value: "descripcion",
          align: "center"
        },
        {
          text: "Racionalizado",
          value: "verificacion_destruccion",
          align: "center"
        },   
      ],
      table: [],
      search: "",
      idpersona:0
        }
  },
  //}),
  computed: {},
  mounted() {
    this.bus.$on("openDialogForm", item  => {
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
      this.bus.$emit("closeDialog",this.selecteditem)
    },
    async getTable() {
      try {
        let res = await axios.get("api/autorizacionrenovacion/showfill/" + this.selectedItem.id)
        this.table = res.data
        console.log(this.table)
      } catch (e) {
        console.log(e);
      }    
    },

    activeItem(item){
      if (item.verificacion_destruccion) {
        this.bus.$emit("openDialogConfirm", {action: "Racionalizado", url: "api/autorizacionrenovacion/"+ item.id, item: {verificacion_destruccion: true}});
      } else {
        this.bus.$emit("openDialogConfirm", {action: "No Racionalizado", url: "api/autorizacionrenovacion/"+ item.id, item: {verificacion_destruccion: false}});
      }
    },
  }
};
</script>
