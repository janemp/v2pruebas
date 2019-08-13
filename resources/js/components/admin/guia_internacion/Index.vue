<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Guías de internación</v-toolbar-title>
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
        :items="table"
        :search="search"
        :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.correlativo }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item.persona) }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.persona.carnet_identidad + ' ' + props.item.persona.departamento_extension.sigla }} </td>
            <td class="justify-center layout">
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="printGuiaItem(props.item)">
                  <v-icon>print</v-icon>
                </v-btn>
                <span>Imprimir</span>
              </v-tooltip>          
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item)">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top v-if="$acl.check('admin')">
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
                      <th class="text-xs-left">Carnet productor: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.persona.carnet_productor.length > 0">
                        {{ props.item.persona.carnet_productor[0].carnet }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="props.item.ruta_id">
                      <th class="text-xs-left">Ruta: </th>
                      <td class="text-xs-left"> {{ props.item.ruta.nombre }} </td>
                    </tr>
                    <tr v-if="props.item.ruta_id">
                      <th class="text-xs-left">Puestos de control: </th>
                      <td class="text-xs-left">
                        <span v-for="(item, index) in props.item.ruta.puestos_de_control" :key="index">
                          {{ item.nombre }}<br>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Orden comunal: </th>
                      <td class="text-xs-left">{{ props.item.orden_comunal }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fecha de orden comunal: </th>
                      <td class="text-xs-left">{{ props.item.fecha_orden_comunal }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Numero de taques: </th>
                      <td class="text-xs-left">{{ props.item.numero_taques }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Libras: </th>
                      <td class="text-xs-left">{{ props.item.libras }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Procedencia: </th>
                      <td class="text-xs-left">{{ props.item.procedencia }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Conductor: </th>
                      <td class="text-xs-left">{{ props.item.conductor }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Licencia: </th>
                      <td class="text-xs-left">{{ props.item.licencia }}</td>
                    </tr>
                    <tr v-if="props.item.vehiculo_id">
                      <th class="text-xs-left">Vehículo: </th>
                      <td class="text-xs-left">{{ props.item.vehiculo.nombre }}</td>
                    </tr>
                    <tr v-if="props.item.marca_id">
                      <th class="text-xs-left">Marca: </th>
                      <td class="text-xs-left">{{ props.item.marca.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Color: </th>
                      <td class="text-xs-left">{{ props.item.color }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Numero de placa: </th>
                      <td class="text-xs-left">{{ props.item.numero_placa }}</td>
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
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue"
import axios from 'axios'
import RemoveItem from "../RemoveItem"
import Form from "./Form"
import print from 'print-js'

export default {
  components: {
    RemoveItem,
    Form,
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Nro. Guía de internación",
        value: "correlativo",
        align: "center"
      },
      {
        text: "Nombre",
        value: "persona.primer_apellido",
        align: "center"
      },
      {
        text: "Carnet de identidad",
        value: "persona.carnet_identidad",
        align: "center"
      },
      // {
      //   text: "Estado",
      //   align: "center",
      //   sortable: false
      // },      
      {
        text: "Opciones",
        align: "center",
        sortable: false,        
      }
    ],
    table: [],
    search: "",
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
      try {
        let res = await axios.get("api/guia_internacion")
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async printGuiaItem(item) {
      try {
        let res = await axios({
          method: "GET",
          url: "api/guia_internacion/print/" + item.id,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {type: "application/pdf"});
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
    },
    async printRetencionItem(item) {
      try {
        let res = await axios({
          method: "GET",
          url: "api/retencion/print/" + item.retencion[0].id,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {type: "application/pdf"});
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      let res = await axios.get("api/control_guia_internacion/fill/" + JSON.stringify({guia_internacion_id: item.id}));
      if(res.data.length > 0) {
        this.$toast.info('Esta guía de internación fue controlada.')
      } else {
        this.bus.$emit("openDialogRemove", `api/guia_internacion/${item.id}`);      
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },    
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
    formatDateTime(date){
      return this.$moment(date).format("DD/MM/YYYY HH:mm")
    }
  }
};
</script>
