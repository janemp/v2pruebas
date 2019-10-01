<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Hojas de ruta</v-toolbar-title>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.codigo_comercializador }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
              <v-chip color="success" text-color="white" v-if="props.item.estado==false">En curso</v-chip>
              <v-chip color="default" text-color="white" v-if="props.item.estado==true">Concluido</v-chip>
            </td>
            <td class="justify-center layout">
              <v-tooltip top v-if="$store.getters['auth/user'].roles.filter(e => e.name === 'admin').length > 0">
                <v-btn slot="activator" flat icon color="indigo" @click="printItem(props.item)">
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
                <v-flex xs2>
                  <v-avatar
                    size="150px">
                    <img v-bind:src="props.item.persona.fotografia">
                  </v-avatar>
                </v-flex>
                <v-flex xs10>
                  <table>
                    <tr>
                      <th class="text-xs-left">Dirección del puesto de venta: </th>
                      <td class="text-xs-left">{{ props.item.puesto_venta.mercado }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Numero de taques: </th>
                      <td class="text-xs-left">{{ props.item.taques }}</td>
                    </tr>                  
                    <tr>
                      <th class="text-xs-left">Fecha inicio validez: </th>
                      <td class="text-xs-left">{{ props.item.fecha_inicio }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fecha final validez: </th>
                      <td class="text-xs-left">{{ props.item.fecha_final }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Procedencia: </th>
                      <td class="text-xs-left">{{ props.item.procedencia.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Destino: </th>
                      <td class="text-xs-left">{{ props.item.destino.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Localidad: </th>
                      <td class="text-xs-left">{{ props.item.localidad.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Destino: </th>
                      <td class="text-xs-left">{{ props.item.destino_coca }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Color: </th>
                      <td class="text-xs-left">{{ props.item.color }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Numero de placa: </th>
                      <td class="text-xs-left">{{ props.item.numero_placa }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Nombre del conductor: </th>
                      <td class="text-xs-left">{{ props.item.nombre_conductor }}</td>
                    </tr>
                    <v-divider></v-divider>
                    <tr>
                      <th>Puestos de control</th>
                      <td>
                        <v-divider></v-divider>
                      <table>
                        <thead>
                          <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(puesto, index) in props.item.ruta.puestos_de_control" :key="index">
                            <td>{{ puesto.nombre }}</td>
                            <td>{{ puesto.direccion }}</td>
                          </tr>
                        </tbody>
                      </table>
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
        text: "ID",
        value: "correlativo",
        align: "center"
      },
      {
        text: "Nombre",
        value: "comercializador.primer_apellido",
        align: "center"
      },
      {
        text: "Carnet de identidad",
        value: "comercializador.carnet_identidad",
        align: "center"
      },
      {
        text: "Codigo Comercializador",
        value: "codigo_comercializador",
        align: "center"
      },
      {
        text: "Estado",
        value: "estado",
        align: "center"
      },      
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    table: [],
    search: ""
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
        let res = await axios.get("api/hoja_ruta")
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async printItem(item) {
      try {        
        let res = await axios({
          method: "GET",
          url: "api/hoja_ruta/print/" + item.id,
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
      this.bus.$emit("openDialogRemove", `api/hoja_ruta/${item.id}`);      
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
