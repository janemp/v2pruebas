<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <RemoveItem :bus="bus"/>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Retenciones</v-toolbar-title>
        <Retencion :bus="bus"/>
        <v-spacer></v-spacer>   
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-tooltip top>
          <v-btn slot="activator" flat icon color="indigo" @click="retenerItem(selectedItem, {mode: 'nuevo'})">
            <v-icon>add_circle</v-icon>
          </v-btn>
          <span>Nuevo</span>
        </v-tooltip>
      </v-toolbar>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="retenciones"
          :search="search"
          :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
          disable-initial-sort
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <tr>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.correlativo }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.inicial_embalaje }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.numero_taques }} </td>
              <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ formatDate(props.item.created_at) }} </td>
              <td class="justify-center layout">              
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="printItem(props.item)">
                    <v-icon>print</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="retenerItem(props.item, {mode: 'editar'})">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top v-if="$store.getters['auth/user'].roles.filter(e => e.name === 'admin').length > 0">
                  <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)" >
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
                        <th class="text-xs-left">Causas: </th>
                        <td class="text-xs-left">
                          <div v-for="(causa, index) in props.item.causas_retencion" :key="index">
                            {{ index + 1 }}.- {{ causa.nombre }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Departamento: </th>
                        <td class="text-xs-left">{{ props.item.departamento.nombre }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Municipio: </th>
                        <td class="text-xs-left">{{ props.item.municipio.nombre }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Localidad: </th>
                        <td class="text-xs-left">{{ props.item.localidad.nombre }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Lugar: </th>
                        <td class="text-xs-left">{{ props.item.lugar }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Descripción: </th>
                        <td class="text-xs-left">{{ props.item.descripcion }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Número de taques: </th>
                        <td class="text-xs-left">{{ props.item.numero_taques }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Libras: </th>
                        <td class="text-xs-left">{{ props.item.libras }}</td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Vehiculo: </th>
                        <td class="text-xs-left">
                          <div v-if="props.item.vehiculo_id">
                            tipo: {{ props.item.vehiculo.nombre }} |
                          </div>
                          <div v-if="props.item.marca_id">
                            marca: {{ props.item.marca.nombre }} |
                          </div>
                          <div v-if="props.item.placa">
                            placa: {{ props.item.placa }}
                          </div>                          
                        </td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Vuelo: </th>
                        <td class="text-xs-left">
                          <div v-if="props.item.numero_vuelo">
                            numero: {{ props.item.numero_vuelo }} |
                          </div>
                          <div v-if="props.item.empresa_vuelo">
                            empresa: {{ props.item.empresaa_vuelo }} |
                          </div>
                          <div v-if="props.item.otros_vuelo">
                            otros: {{ props.item.otros_vuelo }}
                          </div>                          
                        </td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Embarcación: </th>
                        <td class="text-xs-left">
                          <div v-if="props.item.embarcacion_id">
                            tipo: {{ props.item.embarcacion.nombre }} |
                          </div>
                          <div v-if="props.item.numero_embarcacion">
                            numero: {{ props.item.numero_embarcacion }} |
                          </div>
                          <div v-if="props.item.nombre_embarcacion">
                            nombre: {{ props.item.nombre_embarcacion }}
                          </div>                     
                        </td>
                      </tr>
                      <tr>
                        <th class="text-xs-left">Responsable: </th>
                        <td class="text-xs-left">{{ props.item.responsable }}</td>
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
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>        
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import Retencion from './Retencion'
import RemoveItem from "../RemoveItem";

export default {
  components: {
    RemoveItem,
    Retencion
  },  
  props: ["item", "bus"], 
  data() {
    return {
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      retenciones: [],
      search: null,
      headers: [
            {
              text: "Nro. Retención",
              align: "center",
              sortable: false,
            },
            {
              text: "Iniciales embajaje",
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
    this.bus.$on("openDialog", item => {
      this.dialog = true
      this.selectedIndex = item
      this.selectedItem = item
      this.getRetenciones()
    });   
    this.bus.$on("openDialogRetencion", item => {
      this.getRetenciones()
    });
    this.bus.$on("closeDialogRetencion", () => {
      this.getRetenciones()
    }); 
    this.bus.$on("closeDialog", () => {
      this.getRetenciones();
    });
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }
  },
  watch: {
    
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}  
      this.retenciones = []
      this.bus.$emit("closeDialog")
    },
    async getRetenciones() {
      try {
        let res = await axios.get("api/retencion/fill/" + JSON.stringify({persona_id: this.selectedItem.id}))
        this.retenciones = res.data
      } catch (e) {
        console.log(e)
      }
    },
    retenerItem(item, mode) {
      this.bus.$emit("openDialogRetener", Object.assign(item, mode));
    },
    async printItem(item) {
      try {        
        let res = await axios({
          method: "GET",
          url: "api/retencion/print/" + item.id,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {type: "application/pdf"});
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/retencion/${item.id}`);
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
  },  
};
</script>