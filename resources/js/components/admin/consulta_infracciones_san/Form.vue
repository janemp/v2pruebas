<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
       
  <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Infracciones</v-toolbar-title>                 
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.nominfraccion }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.codigo }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.nombre }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.monto }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.descripcion }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded">
              <v-chip color="default" text-color="white" v-if="props.item.estado==0">No cumplido </v-chip>
              <v-chip color="success" text-color="white" v-if="props.item.estado==1">Cumplido</v-chip>
            </td>            
          </tr>         
          </template>  
          <template v-slot:expand="props">
            <v-card flat color="blue-grey lighten-5">
              <v-card-text >
                    <table class="v-table theme--light">                      
                      <tr>
                        <th class="text-xs-left">Adjunto: </th>
                        <td class="text-xs-left">
                          <v-btn icon :href="props.item.adjunto" target="_blank">
                            <v-icon color="red">picture_as_pdf</v-icon>
                          </v-btn>
                        </td>
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
          text: "Nombre Sanción",
          value: "nombre",
          align: "center"
        },
        {
          text: "Monto",
          value: "monto",
          align: "center"
        },
        {
          text: "Descripción",
          value: "descripcion",
          align: "center"
        },
        {
          text: "Estado",
          value: "estado",
          align: "center"
        },
        // {
        //   text: "Activo",
        //   value: "bloqueante",
        //   align: "center"
        // },    
      ],
      table: [],
      search: "",
      idpersona:0
        }
  },
  //}),
  computed: {},
  mounted() {
    this.bus.$on("openDialogSancion", item  => {
      console.log(item)
      this.idpersona= item.id
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
        
        //let res = await axios.get("api/sancion/fill/" + JSON.stringify({'persona_id': this.selectedItem.idpersona}))

        let res = await axios.get("api/sancion/showfill/" + this.selectedItem.id)
        this.table = res.data;
      } catch (e) {
        console.log(e);
      }    
    },
  }
};
</script>
