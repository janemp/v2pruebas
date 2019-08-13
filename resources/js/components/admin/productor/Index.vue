<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Productores</v-toolbar-title>
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
        <ConfirmItem :bus="bus"/>
        <RemoveItem :bus="bus"/>
        <Form :bus="bus"/>
        <Card :bus="bus"/>
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
              <v-tooltip top v-if="props.item.sustitucion_id==null">
                <v-switch
                  slot="activator"
                  v-model="props.item.activo"
                  color="success"
                  @change="activeItem(props.item)"
                  class="mt-2"
                ></v-switch>
                <span>{{ (props.item.activo)? 'Activo':'Inactivo' }}</span>
              </v-tooltip>
              <v-tooltip top v-if="props.item.sustitucion_id==null">
                <v-btn slot="activator" flat icon color="indigo" @click="cardItem(props.item)">
                  <v-icon>credit_card</v-icon>
                </v-btn>
                <span>Carnet de productor</span>
              </v-tooltip>
              <v-tooltip top v-if="props.item.sustitucion_id==null">
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {sustituir: true})">
                  <v-icon>person_add</v-icon>
                </v-btn>
                <span>Sustituir</span>
              </v-tooltip>
              <v-tooltip top v-if="props.item.sustitucion_id==null">
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {sustituir: false})">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top v-if="$acl.check('admin') && props.item.sustitucion_id==null && props.item.cesaciones.length==0">
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
                    <img v-bind:src="props.item.fotografia">
                  </v-avatar>
                </v-flex>
                <v-flex xs9>
                  <table class="v-table theme--light">
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
                    <tr>
                      <th class="text-xs-left">Departamento de nacimiento: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.departamento_nacimiento_id">
                        {{ props.item.departamento_nacimiento.nombre }}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Provincia de nacimiento: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.provincia_nacimiento_id">
                        {{ props.item.provincia_nacimiento.nombre }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="props.item.sustitucion_id">
                      <th colspan="2">SUSTITUCIÓN</th>
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
import Card from "./Card";
import ConfirmItem from "../ConfirmItem"

export default {
  components: {
    RemoveItem,
    ConfirmItem,
    Form,
    Card
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
        let res = await axios.get("api/persona/fill/" + JSON.stringify({'tipo_persona_id': 1}))
        this.table = res.data
      } catch (e) {
        console.log(e)
      }
    },
    cardItem(item) {
      this.bus.$emit("openDialogCard", item);
    }, 
    editItem(item, mode) {
      this.bus.$emit("openDialog", Object.assign(item, mode));
    }, 
    activeItem(item){
      if (item.activo) {
        this.bus.$emit("openDialogConfirm", {action: "Activar", url: "api/persona/"+ item.id, item: {activo: true}});
      } else {
        this.bus.$emit("openDialogConfirm", {action: "Inactivar", url: "api/persona/"+ item.id, item: {activo: false}});
      }
    },
    async removeItem(item) {
      let res = await axios.get("api/guia_internacion/fill/" + JSON.stringify({persona_id: item.id}));
      if(res.data.length > 0) {
        this.$toast.info('Este productor tiene guía de internación.')
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
