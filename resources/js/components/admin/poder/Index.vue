<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Transporte y comercializadores con poder</v-toolbar-title>
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
        <Edit :bus="bus"/>
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
            <td class="text-xs-center"> {{ props.item.persona.nombre }} {{ props.item.persona.primer_apellido }}</td>
            <td class="text-xs-center"> {{ props.item.persona.carnet_identidad }} </td>
            <td class="text-xs-center"> {{ props.item.poder.nombre }} {{ props.item.poder.primer_apellido }} </td>
            <td class="text-xs-center"> {{ props.item.poder.carnet_identidad }} </td>
            <td class="text-xs-center"> {{ props.item.motivo }} </td>
            <td class="text-xs-center"><a :href="props.item.adjuntos" target="blank"><v-icon color="red">picture_as_pdf</v-icon></a></td>
            <td class="justify-center layout">              
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="indigo" @click="editItem(props.item)">
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
            </td>
          </tr>
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
import Edit from "./Edit";

export default {
  components: {
    RemoveItem,
    Form,
    Edit
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Nombre del titular",
        value: "persona.nombre",
        align: "center"
      },
      {
        text: "Carnet del titular",
        value: "persona.carnet_identidad",
        align: "center"
      },
      {
        text: "Nombre del apoderado",
        value: "poder.nombre",
        align: "center"
      },
      {
        text: "Carnet del apoderado",
        value: "poder.carnet_identidad",
        align: "center"
      },
      {
        text: "Motivo",
        value: "motivo",
        align: "center"
      },
      {
        text: "Adjuntos",
        value: "adjuntos",
        align: "center"
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
        let res = await axios.get("api/poder")
        this.table = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialogEdit", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/poder/${item.id}`);      
    },
  }
};
</script>
