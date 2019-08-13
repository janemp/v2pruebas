<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">    
    <v-card>
      <v-toolbar dark color="primary">
      </v-toolbar>
      <v-toolbar>
        <v-toolbar-title>Devoluciones</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>        
        <v-tooltip top>
          <v-btn slot="activator" flat icon color="indigo" @click="nuevoItem(selectedItem)">
            <v-icon>add_circle</v-icon>
          </v-btn>
          <span>Nuevo</span>
        </v-tooltip>
        <DevolucionForm :bus="bus"/>
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
                      <img v-bind:src="props.item.resolucion_administrativa.persona.fotografia">
                    </v-avatar>
                  </v-flex>
                  <v-flex xs9>
                    <table class="v-table theme--light">                      
                      <tr>
                        <th class="text-xs-left">Nombre: </th>
                        <td class="text-xs-left">{{ fullName(props.item.resolucion_administrativa.persona) }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">CI: </th>
                        <td class="text-xs-left">{{ props.item.resolucion_administrativa.persona.carnet_identidad }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Tipo de resolución: </th>
                        <td class="text-xs-left">{{ props.item.resolucion_administrativa.tipo_resolucion.nombre }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Documento adjunto: </th>
                        <td class="text-xs-left">
                          <v-btn icon :href="props.item.resolucion_administrativa.adjunto" target="_blank">
                            <v-icon color="red">picture_as_pdf</v-icon>
                          </v-btn>
                        </td>
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
import DevolucionForm from "./DevolucionForm";

export default {
  components: {
    DevolucionForm
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
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogDevolver", item => {
      this.dialog = true      
      this.selectedItem = item
      this.getTable();
    });    
    this.bus.$on("closeDialogDevolucion", () => {
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
      this.bus.$emit("closeDialog")
    },
    async getTable() {
      try {
        let res = await axios.get("api/devolucion")
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    nuevoItem(item) {
      this.bus.$emit("openDialogDevolucionForm", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/devolucion/${item.id}`)
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
  }
};
</script>