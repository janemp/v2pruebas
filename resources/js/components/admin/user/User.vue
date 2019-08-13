<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Usuarios</v-toolbar-title>
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
        <UserForm :bus="bus"/>
        <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
        :headers="headers"
        :items="users"
        :search="search"
        :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center"> {{ props.item.user_name }} </td>
            <td class="text-xs-center"> {{ props.item.email }} </td>
            <td class="text-xs-center"> {{ props.item.name }} </td>
            <td class="text-xs-center"> {{ props.item.last_name }} </td>
            <td class="text-xs-center"> {{ props.item.mothers_last_name }} </td>
            <td class="text-xs-center"> {{ props.item.created_at | moment("DD/MM  /YYYY") }} </td>
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
import UserForm from "./UserForm";
import { mapGetters } from 'vuex'

export default {
  components: {
    RemoveItem,
    UserForm,
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Usuario",
        value: "user_name",
        align: "center"
      },
      {
        text: "Email",
        value: "email",
        align: "center"
      },
      {
        text: "Nombre",
        value: "name",
        align: "center"
      },
      {
        text: "Primer apellido",
        value: "last_name",
        align: "center"
      },
      {
        text: "Segundo apellido",
        value: "mothers_last_name",
        align: "center"
      },
      {
        text: "Fecha",
        value: "created_at",
        align: "center"
      },
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    users: [],
    search: "",
  }),
  computed: mapGetters({
    auth: 'auth/user'
  }),
  mounted() {
    this.getUsers();
    this.bus.$on("closeDialog", () => {
      this.getUsers();
    });
  },
  methods: {
    async getUsers() {
      try {
        let res = await axios.get("api/user")
        this.users = res.data;
      } catch (e) {
        // console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      if(this.auth.id != item.id) {
        this.bus.$emit("openDialogRemove", `api/user/${item.id}`);
      } else {
        this.$toast.error('No se puede borrar.')
      }
    },
  }
};
</script>
