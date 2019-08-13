<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">    
    <v-card>
      <v-toolbar dark color="primary">
      </v-toolbar>
      <v-toolbar>
        <v-toolbar-title>Donaciones</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>        
        <v-tooltip top>
          <v-btn slot="activator" flat icon color="indigo" @click="nuevoItem(personaId, {mode: 'nuevo'})" :disabled="disableNuevoItem">
            <v-icon>add_circle</v-icon>
          </v-btn>
          <span>Nuevo</span>
        </v-tooltip>
        <DonacionForm :bus="bus"/>
        <!-- <RemoveItem :bus="bus"/> -->
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
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.resolucion_administrativa.resolucion }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.numero_taques }} </td>
              <td class="justify-center layout">
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {mode: 'editar'})" :disabled="props.item.fecha_conclusion < $moment().format('YYYY-MM-DD')">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)" :disabled="props.item.fecha_conclusion < $moment().format('YYYY-MM-DD')">
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
                      <img v-bind:src="props.item.persona.fotografia">
                    </v-avatar>
                  </v-flex>
                  <v-flex xs9>
                    <table class="v-table theme--light">                      
                      <tr>
                        <th class="text-xs-left">Numero de duplicados: </th>
                        <td class="text-xs-left">{{ props.item.duplicado }}</td>
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


      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>        
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Salir</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import DonacionForm from "./DonacionForm";

export default {
  components: {
    DonacionForm
  },  
  props: ["item", "bus"], 
  data() {
    return {
      dialog: false,
      headers: [        
        {
          text: "Resolución",
          align: "resolucion",
          sortable: false,
        },
        {
          text: "Número de taques",
          align: "numero_taques",
          sortable: false,
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
      disableNuevoItem: false,
      personaId: [],
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogDonar", item => {       
      
      this.dialog = true      
      this.selectedItem = item
      this.personaId = item
      this.getTable();      
    });    
    this.bus.$on("closeDialogDonar", () => {
      this.getTable();
    });
  },
  computed: {
    
  },
  watch: {
    
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.personaId = []  
      this.bus.$emit("closeDialogCard")
    },
    async getTable() {
      try {
        let res = await axios.get("api/donacion")
        this.table = res.data
        console.log('res', res.data)
        if(res.data.length > 0) {
          this.disableNuevoItem = true
        }
      } catch (e) {
        console.log(e)
      }
    },
    nuevoItem(item, mode) {
      this.bus.$emit("openDialogDonacionForm", Object.assign(item, mode));
    },
    async removeItem(item) {
      // this.bus.$emit("openDialogRemove", `api/carnet_comercializador/${item.id}`);
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    image(item) {
      return item.fotografia
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY")
    },
    formatDecimal(val){
      return parseFloat(val).toFixed(2)
    },
    async printItem(item) {
      try {        
        let res = await axios({
          method: "GET",
          url: "api/carnet_comercializador/print/" + item.id,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {type: "application/pdf"});
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
    },
  }
};
</script>