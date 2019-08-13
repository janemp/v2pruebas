<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Carnet</v-toolbar-title>
      </v-toolbar>
      <v-toolbar>
        <v-toolbar-title>Carnet de comercializador</v-toolbar-title>
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
        <CardForm :bus="bus"/>
        <RemoveItem :bus="bus"/>
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
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.carnet }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ formatDate(props.item.fecha_emision) }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ formatDate(props.item.fecha_conclusion) }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded">
                <v-chip v-if="props.item.renovado" small color="orange" text-color="white">renovado</v-chip>
                <v-chip v-else small color="green" text-color="white">vigente</v-chip>
              </td>
              <td class="justify-center layout">
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="printItem(props.item)" :disabled="props.item.fecha_conclusion < $moment().format('YYYY-MM-DD')">
                    <v-icon>print</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="duplicarItem(props.item, {mode: 'duplicar'})" :disabled="props.item.fecha_conclusion < $moment().format('YYYY-MM-DD')">
                    <v-icon>{{ (props.item.duplicado>9)?'filter_9_plus':'filter_' + (props.item.duplicado+1) }}</v-icon>
                  </v-btn>
                  <span>Duplicar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="renovarItem(props.item, {mode: 'renovar'})" :disabled="props.item.fecha_conclusion > $moment().format('YYYY-MM-DD')||props.item.renovado">
                    <v-icon>filter</v-icon>
                  </v-btn>
                  <span>Renovar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {mode: 'editar'})" :disabled="props.item.fecha_conclusion < $moment().format('YYYY-MM-DD')">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top v-if="$store.getters['auth/user'].roles.filter(e => e.name === 'admin').length > 0">
                  <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
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
import CardForm from "./CardForm";
import RemoveItem from "../RemoveItem";

export default {
  components: {
    RemoveItem,
    CardForm
  },  
  props: ["item", "bus"], 
  data() {
    return {
      dialog: false,
      headers: [        
        {
          text: "Carnet de productor",
          align: "center",
          sortable: false,
        },
        {
          text: "Fecha emisión",
          align: "center",
          sortable: false,
        },
        {
          text: "Fecha conclusión",
          align: "center",
          sortable: false,
        },
        {
          text: "Estado",
          align: "center",
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
    this.bus.$on("openDialogCard", item => {       
      
      this.dialog = true      
      this.selectedItem = item
      this.personaId = item
      this.getTable();      
    });    
    this.bus.$on("closeDialogCard", () => {
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
        let res = await axios.get("api/carnet_comercializador/fill/" + JSON.stringify({persona_id: this.personaId.id}))
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
      this.bus.$emit("openDialogCardForm", Object.assign(item, mode));
    },
    editItem(item, mode) {
      this.bus.$emit("openDialogCardForm", Object.assign(item, mode));
    },
    duplicarItem(item, mode){
      this.bus.$emit("openDialogCardForm", Object.assign(item, mode));
    },
    renovarItem(item, mode){
      this.bus.$emit("openDialogCardForm", Object.assign(item, mode));
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/carnet_comercializador/${item.id}`);
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