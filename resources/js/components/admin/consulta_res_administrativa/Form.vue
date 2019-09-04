<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Resolución Administrativa</v-toolbar-title>
    
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
          :items="resoluciones"
          disable-initial-sort
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <tr>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.resolucion }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ formatDate(props.item.fecha_resolucion) }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.tipo_resolucion.nombre }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.numero_taques }} </td>
            </tr>
          </template> 
          <template v-slot:expand="props">
            <v-card flat color="blue-grey lighten-5">
              <v-card-text >
                    <table class="v-table theme--light">
                      <tr>
                        <th class="text-xs-left">Observaciones: </th>
                        <td class="text-xs-left"> {{ props.item.observaciones }} </td>
                      </tr>
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
import Vue from 'vue'
import axios from 'axios'

export default {
  components: {

  },  
  props: ["item", "bus"], 
  data() {
    return {
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      resoluciones: [],
      search: null,
      headers: [
            {
              text: "Nro. Resolución",
              align: "center",
              sortable: false,
            },
            {
              text: "Fecha Resolución",
              align: "center",
              sortable: false,
            }, 
            {
              text: "Tipo de Resolución",
              align: "center",
              sortable: false,
            },   
            {
              text: "Taques",
              align: "center",
              sortable: false,
            },    
                      
          ],
    };
  },

  mounted() {
    this.bus.$on("openDialogForm", item => {
      this.dialog = true  
      this.selectedItem = item
      this.getResoluciones()
    });  
  },
 
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}  
      this.resoluciones = []
      
      this.bus.$emit("closeDialog")
    },
    
    async getResoluciones() {
      try {        
        let res = await axios.get("api/resolucion_administrativa/fill/" + JSON.stringify({persona_id: this.selectedItem.id}))
        this.resoluciones = res.data
      } catch (e) {
        console.log(e)
      }
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    }, 
  },  
};
</script>