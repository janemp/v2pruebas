<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Control de hojas de ruta</v-toolbar-title>
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
        <Qr :bus="bus"/>     
        <!-- <Form :bus="bus"/> -->
        <RemoveItem :bus="bus"/>
        <Control :bus="bus"/>
        <Retener :bus="bus"/>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.hoja_ruta.correlativo }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item.hoja_ruta.persona) }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.hoja_ruta.persona.carnet_identidad + ' ' + props.item.hoja_ruta.persona.departamento_extension.sigla }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.hoja_ruta.codigo_comercializador }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded">
              <v-chip color="success" text-color="white" v-if="props.item.hoja_ruta.estado==false">En curso</v-chip>
              <v-chip color="error" text-color="white" v-if="props.item.hoja_ruta.estado==true">Terminado</v-chip>
            </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded">
              <v-chip color="success" text-color="white" v-if="props.item.hoja_ruta.retenido==false">No retenido </v-chip>
              <v-chip color="error" text-color="white" v-if="props.item.hoja_ruta.retenido==true">Retenido</v-chip>
            </td>              
            <td class="justify-center layout">
              <v-tooltip v-if="props.item.hoja_ruta.estado == false && props.item.hoja_ruta.retenido == false" top>
                <v-btn slot="activator" flat icon color="indigo" @click="controlItem(props.item)">
                  <v-icon>where_to_vote</v-icon>
                </v-btn>
                <span>Estado de puestos de control</span>
              </v-tooltip>
              <v-tooltip top>
                <v-btn v-if="props.item.hoja_ruta.retenido==false" slot="activator" flat icon color="indigo" @click="retenerItem(props.item)">
                  <v-icon>backspace</v-icon>
                </v-btn>
                <span>Retener</span>
              </v-tooltip>
              <v-menu offset-y>
                <v-tooltip top slot="activator">
                  <v-btn slot="activator" flat icon color="indigo">
                    <v-icon>print</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
                </v-tooltip>
                <v-list>
                  <v-list-tile @click="printItem(props.item)">
                    <v-list-tile-title>Hoja de ruta</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>

            </td>
          </tr>
        </template>
        <template v-slot:expand="props">
          <v-card>
            <v-card-text >
              <v-layout row wrap>
                <v-flex xs2>
                  <v-avatar
                    size="150px">
                    <img v-bind:src="props.item.hoja_ruta.persona.fotografia">
                  </v-avatar>
                </v-flex>
                <v-flex xs5>
                  <table>
                    <tr>
                      <th class="text-xs-left">Numero de taques: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.taques }}</td>
                    </tr>                  
                    <tr>
                      <th class="text-xs-left">Dirección de Puesto: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.puesto_venta.mercado }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Procedencia: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.procedencia.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Conductor: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.nombre_conductor }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Vehiculo: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.vehiculo.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Marca: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.marca.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Color: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.color }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Número de placa: </th>
                      <td class="text-xs-left">{{ props.item.hoja_ruta.numero_placa }}</td>
                    </tr>
                  </table>
                </v-flex>
                <v-flex xs5>
                  <table v-if="props.item.decomisado">
                    <caption><v-chip color="primary" text-color="white">Decomisado</v-chip></caption>
                    <tr>
                      <th class="text-xs-left">ID Retencion: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.id_retencion }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Nombre: </th>
                      <td class="text-xs-left">{{ fullName(props.item.decomiso_retencion) }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Carnet de productor: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.carnet_productor }}</td>
                    </tr>                  
                    <tr>
                      <th class="text-xs-left">Iniciales de embalaje productor: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.iniciales_embalaje_productor }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Iniciales de embalaje comercializador: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.iniciales_embalaje_comercializador }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Causa de retención: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.causa_retencion.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Lugar: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.lugar }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fecha y  hora: </th>
                      <td class="text-xs-left">{{ formatDateTime(props.item.decomiso_retencion.fecha_hora) }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Localidad: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.localidad.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Departamento: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.departamento.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Provincia: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.provincia.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Municipio: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.municipio.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Descripción del hecho: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.descripcion_hecho }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Cantidad de taques/bultos: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.cantidad_taques_bultos }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Libras: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.libras }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.vehiculo_id">
                      <th class="text-xs-left">Vehículo: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.vehiculo.nombre }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.marca_id">
                      <th class="text-xs-left">Marca de vehículo: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.marca.nombre }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.vehiculo_id">
                      <th class="text-xs-left">Placa de vehículo: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.terrestre_placa }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.vehiculo_id">
                      <th class="text-xs-left">Empresa aerea: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.aereo_empresa }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.vehiculo_id">
                      <th class="text-xs-left">Número de vuelo: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.aereo_numero_vuelo }}</td>
                    </tr> 
                    <tr v-if="props.item.decomiso_retencion.vehiculo_id">
                      <th class="text-xs-left">Otros datos del vuelo: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.aereo_otros }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.embarcacion_id">
                      <th class="text-xs-left">Embarcación: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.embarcacion.nombre }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.embarcacion_id">
                      <th class="text-xs-left">Nombre de embarcacion: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.fluvial_nombre_embarcacion }}</td>
                    </tr>
                    <tr v-if="props.item.decomiso_retencion.embarcacion_id">
                      <th class="text-xs-left">Registro de embarcacion: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.fluvial_numero_registro }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Responsable: </th>
                      <td class="text-xs-left">{{ props.item.decomiso_retencion.responsable.nombre }}</td>
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
import print from 'print-js'
import RemoveItem from "../RemoveItem"
import Control from './Control'
import Retener from './Retener'
import Qr from './Qr'


export default {
  components: {
    RemoveItem,
    // Form,
    Control,
    Qr,
    Retener
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "ID",
        value: "hoja_ruta.id_ruta",
        align: "center"
      },
      {
        text: "Productor/Comercializador",
        value: "hoja_ruta.persona.primer_apellido",
        align: "center"
      },
      {
        text: "Carnet de identidad",
        value: "hoja_ruta.persona.carnet_identidad",
        align: "center"
      },
      {
        text: "Codigo Comercializador",
        value: "hoja_ruta.codigo_comercializador",
        align: "center"
      },
      {
        text: "Estado",
        align: "center",
        sortable: false
      },  
      {
        text: "Decomisado",
        align: "center",
        sortable: false
      },     
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    table: [],
    search: "",
    table2: []
  }),
  computed: {},
  mounted() {
    this.getTable();
    this.bus.$on("closeDialogControl1", () => {
      this.getTable();
    });
    this.bus.$on("closeDialogRefresh", () => {
      this.getTable();
    });
    this.bus.$on("closeDialogOpcion", () => {
      this.getTable();
    });
    this.bus.$on('closeDialog', () => {
      this.getTable();
    });
  },
  methods: {
    async getTable() {
      try {
        let res = await axios.get("api/control_hoja_ruta")
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    refresh() {
      axios.get("api/control_hoja_ruta").then(res => {
        this.table = res.data
      })
    },
    async printItem(item) {
      try {        
        let res = await axios({
          method: "GET",
          url: "api/hoja_ruta/print/" + item.hoja_ruta.id,
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
          url: "api/retencion/printhoja/" + item.hoja_ruta.retencion[0].id,
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
    controlItem(item){
      this.bus.$emit("openDialogControl", item);
    },
    retenerItem(item) {
      this.bus.$emit("openDialogRetener", item);
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
    },
  }
};
</script>
