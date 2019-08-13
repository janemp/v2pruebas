<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Representantes y organizaciones beneficiarias de donaciones</v-toolbar-title>
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
        <RemoveItem :bus="bus"/>
        <Form :bus="bus"/>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.nombre }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.direccion }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.telefono }} </td>
            <td class="justify-center layout">
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item, {sustituir: false})">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip topv-if="$store.getters['auth/user'].roles.filter(e => e.name === 'admin').length > 0">
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
                      <th class="text-xs-center" colspan="2">REPRESENTANTE LEGAL</th>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Nombre: </th>
                      <td class="text-xs-left">{{ fullName(props.item.persona) }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">CI: </th>
                      <td class="text-xs-left">{{ props.item.persona.carnet_identidad + ' ' + props.item.persona.departamento_extension.sigla }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">CI: </th>
                      <td class="text-xs-left">{{ props.item.persona.carnet_identidad + ' ' + props.item.persona.departamento_extension.sigla }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Estado civil: </th>
                      <td class="text-xs-left">{{ props.item.persona.estado_civil }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Genero: </th>
                      <td class="text-xs-left">{{ (props.item.persona.genero=='M')?'Masculino':'Femenino' }}</td>
                    </tr>                  
                    <tr>
                      <th class="text-xs-left">Telefono: </th>
                      <td class="text-xs-left">{{ props.item.persona.telefono }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Email: </th>
                      <td class="text-xs-left">{{ props.item.persona.email }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Domicilio: </th>
                      <td class="text-xs-left">{{ props.item.persona.domicilio }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Fecha de nacimiento: </th>
                      <td class="text-xs-left">{{ formatDate(props.item.persona.fecha_nacimiento) }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Pais de nacimiento: </th>
                      <td class="text-xs-left">{{ props.item.persona.pais_nacimiento.nombre }}</td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Departamento de nacimiento: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.persona.departamento_nacimiento_id">
                        {{ props.item.persona.departamento_nacimiento.nombre }}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-xs-left">Provincia de nacimiento: </th>
                      <td class="text-xs-left">
                        <span v-if="props.item.persona.provincia_nacimiento_id">
                        {{ props.item.persona.provincia_nacimiento.nombre }}
                        </span>
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

export default {
  components: {
    RemoveItem,
    Form
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Organización",
        value: "nombre",
        align: "center"
      },
      {
        text: "Direccion",
        value: "direccion",
        align: "center"
      },      
      {
        text: "Telefono",
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
        let res = await axios.get("api/beneficiario_donacion")
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
