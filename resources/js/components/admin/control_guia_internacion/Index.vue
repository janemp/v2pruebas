<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Control de guías de internación</v-toolbar-title>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
              <v-chip v-if="props.item.retencion.length>0" small color="red" text-color="white">Retenido</v-chip>
              <v-chip v-if="props.item.control_guia_internacion.length>0" small color="green darken-2" text-color="white">Controlado</v-chip>
            </td>            
            <td class="justify-center layout">
              <v-menu offset-y v-if="$acl.check('admin')">
                <v-tooltip top slot="activator">
                  <v-btn slot="activator" flat icon color="indigo">
                    <v-icon>print</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
                </v-tooltip>
                <v-list>
                  <v-list-tile @click="printGuiaItem(props.item)">
                    <v-list-tile-title>Guía de internación</v-list-tile-title>
                  </v-list-tile>
                  <v-list-tile @click="printRetencionItem(props.item)" v-if="props.item.retencion.length>0">
                    <v-list-tile-title>Retención</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>
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
                      <td class="text-xs-left">{{ (props.item.persona.carnet_productor.length > 0)?props.item.persona.carnet_productor[0].carnet:'-' }}</td>
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
                  <table class="v-table theme--light" v-if="props.item.retencion.length>0">
                    <tr>
                      <th colspan="2">RETENCION</th>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Iniciales de embalaje: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].inicial_embalaje }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Causas de retención: </th>
                      <td class="text-xs-left" colspan="3">
                        <v-list>
                          <v-list-tile v-for="(causa, index) in props.item.retencion[0].causas_retencion" :key="index">
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
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].departamento.nombre }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Municipio: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].municipio.nombre }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Localidad: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].localidad.nombre }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Lugar: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].lugar }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Descripción: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].descripcion }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Numero de taques: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].numero_taques }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Libras: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].libras }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Terrestre: </th>
                      <td class="text-xs-left">Número de placa:
                        {{ props.item.retencion[0].numero_placa }}
                      </td>
                      <td class="text-xs-left" v-if="props.item.retencion[0].vehiculo_id">Tipo:
                        {{ props.item.retencion[0].vehiculo.nombre }}
                      </td>
                      <td class="text-xs-left" v-if="props.item.retencion[0].marca_id">Marca:
                        {{ props.item.retencion[0].marca.nombre }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Aereo: </th>
                      <td class="text-xs-left">Número de vuelo:
                        {{ props.item.retencion[0].numero_vuelo }}
                      </td>
                      <td class="text-xs-left">Empresa:
                        {{ props.item.retencion[0].empresa_vuelo }}
                      </td>
                      <td class="text-xs-left">Otros:
                        {{ props.item.retencion[0].otros_vuelo }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fluvial: </th>
                      <td class="text-xs-left">Nombre embarcación:
                        {{ props.item.retencion[0].nombre_embarcacion }}
                      </td>
                      <td class="text-xs-left" v-if="props.item.retencion[0].embarcacion_id">Tipo:
                        {{ props.item.retencion[0].embarcacion.nombre }}
                      </td>
                      <td class="text-xs-left">Número embarcación:
                        {{ props.item.retencion[0].numero_embarcacion }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Responsable: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ props.item.retencion[0].responsable }}
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fecha y hora: </th>
                      <td class="text-xs-left" colspan="3">
                        {{ formatDateTime(props.item.retencion[0].created_at) }}
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
      {
        text: "Estado",
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
        let res = await axios.get("api/guia_internacion/fill/" + JSON.stringify({controlado: true}))
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async printItem(item) {
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
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/control_guia_internacion/${item.id}`);      
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