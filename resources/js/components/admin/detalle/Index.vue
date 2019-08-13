<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Productorera al detalle</v-toolbar-title>
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
        <Cesar :bus="bus"/>
        <Form :bus="bus"/>
        <RemoveItem :bus="bus"/>
        <ActiveItem :bus="bus"/>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item) }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.carnet_identidad + ' ' + props.item.departamento_extension.sigla }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> 
              <v-chip v-if="props.item.sustitucion_id" small color="blue" text-color="white">Sustituido</v-chip>
              <v-chip v-if="props.item.cesaciones.length>0" small color="grey" text-color="white">Cesado</v-chip>
              <v-chip v-if="props.item.sustitucion_id==null && props.item.cesaciones.length==0" small color="green" text-color="white">Vigente</v-chip>
            </td>
            <td class="justify-center layout">
              <span v-if="props.item.sustitucion_id==null && props.item.cesaciones.length==0">               
                <v-tooltip top v-if="props.item.tipo_persona_id==2">
                  <v-btn slot="activator" flat icon color="indigo" @click="cesarItem(props.item)">
                    <v-icon>cancel</v-icon>
                  </v-btn>
                  <span>Cesación</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {sustituir: true})">
                    <v-icon>person_add</v-icon>
                  </v-btn>
                  <span>Sustituir</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {sustituir: false})">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                    <v-icon>delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
              </span>
              <v-tooltip top v-if="props.item.cesaciones.length>0">
                <v-btn slot="activator" flat icon color="indigo" @click="reactivarItem(props.item)">
                  <v-icon>add_circle_outline</v-icon>
                </v-btn>
                <span>Quitar cesación</span>
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
                    <img v-bind:src="props.item.fotografia">
                  </v-avatar>
                </v-flex>
                <v-flex xs9>
                  <table>
                    <tr>
                      <th class="text-xs-left">Estado civil: </th>
                      <td class="text-xs-left">{{ props.item.estado_civil }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Genero: </th>
                      <td class="text-xs-left">{{ (props.item.genero=='M')?'Masculino':'Femenino' }}</td>
                    </tr>                  
                    <tr>
                      <th class="text-xs-left">Telefono: </th>
                      <td class="text-xs-left">{{ props.item.telefono }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Email: </th>
                      <td class="text-xs-left">{{ props.item.email }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Domicilio: </th>
                      <td class="text-xs-left">{{ props.item.domicilio }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fecha de nacimiento: </th>
                      <td class="text-xs-left">{{ formatDate(props.item.fecha_nacimiento) }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Pais de nacimiento: </th>
                      <td class="text-xs-left">{{ props.item.pais_nacimiento.nombre }}</td>
                    </tr>
                    <tr v-if="props.item.departamento_nacimiento_id">
                      <th class="text-xs-left">Departamento de nacimiento: </th>
                      <td class="text-xs-left">{{ props.item.departamento_nacimiento.nombre }}</td>
                    </tr>
                    <tr v-if="props.item.provincia_nacimiento_id">
                      <th class="text-xs-left">Provincia de nacimiento: </th>
                      <td class="text-xs-left">{{ props.item.provincia_nacimiento.nombre }}</td>
                    </tr>                    
                    <tr v-if="props.item.tipo_persona_id==1||props.item.tipo_persona_id==3">
                      <th colspan="2"><hr></th>
                    </tr>                    
                    <tr v-if="props.item.sustitucion_id">
                      <th colspan="2"><hr></th>
                    </tr>
                    <tr v-if="props.item.sustitucion_id">
                      <th class="text-xs-left">Sustituido por: </th>
                      <td class="text-xs-left">
                        {{ fullName(props.item.persona) }} <br>     
                        CI.: {{ props.item.persona.carnet_identidad + ' ' + props.item.persona.departamento_extension.sigla }}                   
                      </td>
                    </tr>
                    <tr v-if="props.item.sustitucion_id">
                      <th class="text-xs-left">Motivo de sustitución: </th>
                      <td class="text-xs-left">{{ props.item.sustitucion.descripcion }} </td>
                    </tr>
                    <tr v-if="props.item.sustitucion_id">
                      <th class="text-xs-left">Documento de sustitución: </th>
                      <td class="text-xs-left">
                        <v-btn icon :href="props.item.adjunto_sustitucion" target="_blank">
                          <v-icon color="red">picture_as_pdf</v-icon>
                        </v-btn>
                      </td>
                    </tr>
                    <tr v-if="props.item.cesaciones.length>0">
                      <th colspan="2"><hr></th>
                    </tr>
                    <tr v-if="props.item.cesaciones.length>0">
                      <th class="text-xs-left">Motivo de cesación: </th>
                      <td class="text-xs-left">{{ props.item.cesaciones[0].descripcion }} </td>
                    </tr>
                    <tr v-if="props.item.cesaciones.length>0">
                      <th class="text-xs-left">Documento de cesación: </th>
                      <td class="text-xs-left">
                        <v-btn icon :href="props.item.cesaciones[0].pivot.adjunto_cesacion" target="_blank">
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
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
import axios from 'axios'
import RemoveItem from "../RemoveItem";
import Form from "./Form";
import Cesar from "./Cesar";
import ActiveItem from "../ActiveItem";

export default {
  components: {
    RemoveItem,
    Form,
    Cesar,
    ActiveItem
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Nombre",
        value: "primer_apellido",
        align: "center"
      },
      {
        text: "Carnet de identidad",
        value: "carnet_identidad",
        align: "center"
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
        let res = await axios.get("api/persona/fill/" + JSON.stringify({'tipo_persona_id': 3}))
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    cesarItem(item) {
      this.bus.$emit("openDialogCesar", item);
    },
    editItem(item, mode) {
      this.bus.$emit("openDialog", Object.assign(item, mode));
    },
    reactivarItem(item) {
      this.bus.$emit("openDialogActive", "api/persona_cesacion/"+item.cesaciones[0].pivot.id);
    },
    async removeItem(item) {
      let res = await axios.get("api/guia_internacion/fill/" + JSON.stringify({persona_id: item.id}));
      let res2 = await axios.get("api/guia_internacion/fill/" + JSON.stringify({persona_id: item.id}));
      if(res.data.length > 0 && res2.data.length > 0) {
        this.$toast.info('Este productor comercializador al detalle tiene hoja de ruta o guía de internación.')
      } else {
        this.bus.$emit("openDialogRemove", `api/persona/${item.id}`);
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    image(item) {
      return item.fotografia
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY")
    }
  }
};
</script>
