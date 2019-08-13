<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Depositos</v-toolbar-title>
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
        <Decomiso :bus="bus"/>
        <Devolucion :bus="bus"/>
        <Donacion :bus="bus"/>
        <Salida :bus="bus"/>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.retencion.correlativo }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ fullName(props.item.retencion.persona) }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.retencion.persona.carnet_identidad }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.numero_taques }} </td>
            <td class="justify-center layout">
              
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
                <v-flex xs3>
                  <v-avatar
                    size="150px"
                    v-if="props.item.retencion.persona_id">
                    <img v-bind:src="props.item.retencion.persona.fotografia">
                  </v-avatar>                  
                  <v-avatar
                    size="150px"
                    v-else>
                    <img v-bind:src="'img/user.png'">
                  </v-avatar>
                </v-flex>
                <v-flex xs9>
                  <table class="v-table theme--light">
                    <tr>
                      <th class="text-xs-left">Fecha de deposito:</th>
                      <td class="text-xs-left">
                        {{ formatDate(props.item.created_at) }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Estado: </th>
                      <td class="text-xs-left">
                        {{ props.item.estado }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Observaciones: </th>
                      <td class="text-xs-left">
                        {{ props.item.observaciones }}
                      </td>
                    </tr>
                    <tr>
                      <th colspan="2">DATOS DE DEVOLUCIÓN</th>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <table>
                          <span v-for="(item, index) in props.item.devoluciones" :key="index">
                            <tr>
                              <th class="text-xs-left">Resolución administrativa: </th>
                              <td class="text-xs-left">
                                {{ item.resolucion_administrativa.resolucion }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Numero de taques: </th>
                              <td class="text-xs-left">
                                {{ item.numero_taques  }}
                              </td>
                            </tr>
                          </span>
                        </table>
                      </td>
                    </tr>

                    <tr>
                      <th colspan="2">DATOS DE DECOMISO</th>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <table>
                          <span v-for="(item, index) in props.item.decomisos" :key="index">
                            <tr>
                              <th class="text-xs-left">Resolución administrativa: </th>
                              <td class="text-xs-left">
                                {{ item.resolucion_administrativa.resolucion }}
                              </td>
                            </tr>
                            <tr>
                              <th class="text-xs-left">Numero de taques: </th>
                              <td class="text-xs-left">
                                {{ item.numero_taques_ingreso  }}
                              </td>
                            </tr>
                          </span>
                        </table>
                      </td>
                    </tr>

                    <tr>
                      <th colspan="2">DATOS DE RETENCIÓN</th>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Nro. retención: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.correlativo }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Documento: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.retencion.guia_internacion_id">
                        Nro. guía de internación: {{ props.item.retencion.guia_internacion.correlativo }}
                        </span>
                        <span v-if="props.item.retencion.hoja_ruta_id">
                        Nro. hoja de ruta: {{ props.item.retencion.hoja_ruta.correlativo }}
                        </span>
                        <span v-if="props.item.retencion.guia_internacion_id==null&&props.item.retencion.hoja_ruta_id==null">
                          Sin documentos
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Nombres y apellidos: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.retencion.persona_id">
                        {{ fullName(props.item.retencion.persona) }}
                        </span>                        
                        <span v-else>
                        {{ fullName(props.item.retencion) }}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Cedula identidad: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.retencion.persona_id">
                        {{ props.item.retencion.persona.carnet_identidad+' '+props.item.retencion.persona.departamento_extension.sigla }}
                        </span>
                        <span v-else>
                        {{ props.item.retencion.carnet_identidad }}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Iniciales de embalaje: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.inicial_embalaje }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Causas de retención: </th>
                      <td class="text-xs-left">
                        <v-list>
                          <v-list-tile v-for="(causa, index) in props.item.retencion.causas_retencion" :key="index">
                            <v-list-tile-content>
                              <v-list-tile-title class="caption">
                              {{ causa.nombre }}
                              </v-list-tile-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </v-list>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Departamento: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.departamento.nombre }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Municipio: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.municipio.nombre }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Localidad: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.localidad.nombre }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Lugar: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.lugar }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Descripción: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.descripcion }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Numero de taques: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.numero_taques }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Libras: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.libras }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Terrestre: </th>
                      <td class="text-xs-left">Número de placa:
                        {{ props.item.retencion.numero_placa }}
                      </td>
                      <td class="text-xs-left" v-if="props.item.retencion.vehiculo_id">Tipo:
                        {{ props.item.retencion.vehiculo.nombre }}
                      </td>
                      <td class="text-xs-left" v-if="props.item.retencion.marca_id">Marca:
                        {{ props.item.retencion.marca.nombre }}
                      </td>
                    </tr>
                     <tr>
                      <th class="text-xs-left">Aereo: </th>
                      <td class="text-xs-left">Número de vuelo:
                        {{ props.item.retencion.numero_vuelo }}
                      </td>
                      <td class="text-xs-left">Empresa:
                        {{ props.item.retencion.empresa_vuelo }}
                      </td>
                      <td class="text-xs-left">Otros:
                        {{ props.item.retencion.otros_vuelo }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fluvial: </th>
                      <td class="text-xs-left">Nombre embarcación:
                        {{ props.item.retencion.nombre_embarcacion }}
                      </td>
                      <td class="text-xs-left" v-if="props.item.retencion.embarcacion_id">Tipo:
                        {{ props.item.retencion.embarcacion.nombre }}
                      </td>
                      <td class="text-xs-left">Número embarcación:
                        {{ props.item.retencion.numero_embarcacion }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Responsable: </th>
                      <td class="text-xs-left">
                        {{ props.item.retencion.responsable }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fecha y hora de retención: </th>
                      <td class="text-xs-left">
                        {{ formatDateTime(props.item.retencion.created_at) }}                        
                      </td>
                    </tr>
                  </table>
                </v-flex>
              </v-layout>
              </v-card-text>
          </v-card>
        </template>
        <template v-slot:footer>
          <td colspan="3">
            <strong>Totales </strong> 
          </td>
          <th class="text-xs-center">
            {{ totalDeposito }} 
          </th>
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
import Salida from "./Salida"
import print from 'print-js'
import Devolucion from "./Devolucion"
import Decomiso from "./Decomiso"
import Donacion from "./Donacion"
import { constants } from 'crypto';
export default {
  components: {
    RemoveItem,
    Form,
    Salida,
    Decomiso,
    Devolucion,
    Donacion,
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Nro. retención",
        value: "correlativo",
        align: "center"
      },      
      {
        text: "Nombre",
        value: "retencion.persona.paterno",
        align: "center"
      },
      {
        text: "CI.",
        value: "retencion.persona.carnet_identidad",
        align: "center"
      },
      {
        text: "N° taques ingresado",
        value: "retencion.persona.nombre",
        align: "center"
      },
      // {
      //   text: "N° taques devuelto",
      //   align: "center",
      //   sortable: false
      // }, 
      // {
      //   text: "N° taques decomisado",
      //   align: "center",
      //   sortable: false
      // },      
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    table: [],
    search: "",
    saldos: [],
    totalDeposito: 0,
    totalDevuelto: 0,
    totalDecomiso: 0,
  }),
  computed: {},
  mounted() {
    this.getTable()
    this.bus.$on("closeDialog", () => {
      this.getTable()
    });
  },
  methods: {
    async getTable() {
      try {
        let res = await axios.get("api/deposito")
        this.table = res.data
        this.totalDeposito = 0
        this.totalDevuelto = 0
        this.totalDecomiso = 0
        res.data.forEach(function(item, index, array) {
          this.totalDeposito = this.totalDeposito + item.numero_taques
            item.devoluciones.forEach(function(itemDev){
              this.totalDevuelto = this.totalDevuelto + itemDev.numero_taques
            },this)
          item.decomisos.forEach(function(itemDec){
            this.totalDecomiso = this.totalDecomiso + itemDec.numero_taques
          },this)
        }, this);
      } catch (e) {
        console.log(e)
      }
    },
    devolverItem(item) {
      this.bus.$emit("openDialogDevolver", item);
    },
    decomisarItem(item) {
      this.bus.$emit("openDialogDecomisar", item);
    },
    donarItem(item) {
      this.bus.$emit("openDialogDonar", item);
    },
    exitItem(item, mode) {
      this.bus.$emit("openDialogExit", Object.assign(item, mode));
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      let res = await axios.get("api/deposito/fill/" + JSON.stringify({id: item.id, tipo: 'SALIDA'}));
      if(res.data.length > 0) {
        this.$toast.info("Este deposito ya tiene salida.")
      } else {
        this.bus.$emit("openDialogRemove", `api/deposito/${item.id}`);      
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
    },
    devuelto(item){
      let total = 0
      item.devoluciones.forEach(function(item) {
        total =  total + item.numero_taques
      });
      return total
    },
    decomisado(item){
      let total = 0
      item.decomisos.forEach(function(item) {
        total =  total + item.numero_taques
      });
      return total
    },
    verificaDeposito(item){
      let dev = 0
      let dec = 0
      item.devoluciones.forEach(function(item) {
        dev =  dev + item.numero_taques
      });
      item.decomisos.forEach(function(item) {
        dec =  dec + item.numero_taques
      });
      if((dev+dec) >= item.numero_taques){
        return true
      } else {
        return false
      }
    }
  }
};
</script>