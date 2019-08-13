<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Resoluciones Administrativas</v-toolbar-title>
        <Form :bus="bus"/>
        <RemoveItem :bus="bus"/>
        <v-spacer></v-spacer>   
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-tooltip top>
          <v-btn slot="activator" flat icon color="indigo" @click="editItem(selectedItem, {mode: 'nuevo'})">
            <v-icon>add_circle</v-icon>
          </v-btn>
          <span>Nuevo</span>
        </v-tooltip>
      </v-toolbar>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="resoluciones"
          :search="search"
          :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
          disable-initial-sort
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <tr>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.resolucion }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.tipo_resolucion.nombre }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.numero_taques }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ formatDate(props.item.created_at) }} </td>
              <td class="justify-center layout">
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {mode: 'editar'})">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="removeItem(props.item)" :disabled="!$store.getters['auth/user'].roles.filter(e => e.name === 'admin').length > 0">
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
import Vue from 'vue'
import axios from 'axios'
import RemoveItem from "../RemoveItem";
import Form from "./Form";

export default {
  components: {
    RemoveItem,
    Form
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
              text: "Nro. Resolucion",
              align: "center",
              sortable: false,
            },
            {
              text: "Tipo de resolución",
              align: "center",
              sortable: false,
            },
            {
              text: "Taques",
              align: "center",
              sortable: false,
            },
            {
              text: "Fecha",
              align: "center",
              sortable: false,
            },
            {
              text: "Opciones",
              align: "center",
              sortable: false,
            },
          ],
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogResolucion", item => {
      this.dialog = true
      this.selectedItem = item
      this.getResoluciones()
    });
    this.bus.$on("closeDialogForm", () => {
      this.getResoluciones()
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
    editItem(item, mode) {
      this.bus.$emit("openDialogForm", Object.assign(item, mode));
    },
    removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/resolucion_administrativa/${item.id}`);
      this.getResoluciones()
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
  },  
};
</script>