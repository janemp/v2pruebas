<template>
  <div>
    <h2 class="mb-3 primary--text headline">Tu perfil</h2>

    <v-card>
      <v-card-text>
        <v-text-field
            label="Nombre de usuario"
            v-model="user.user_name"
            box
            readonly
        ></v-text-field>
        <v-text-field
            label="Email"
            v-model="user.email"
            box
            readonly
        ></v-text-field>
        <v-text-field
            label="Nombre"
            v-model="user.name"
            box
            readonly
        ></v-text-field>
        <v-text-field
            label="Primer apellido"
            v-model="user.primer_apellido"
            box
            readonly
        ></v-text-field>
        <v-text-field
            label="Segundo apellido"
            v-model="user.segundo_apellido"
            box
            readonly
        ></v-text-field>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    data: () => ({
      user: {
        name: null,
        email: null,
      }
    }),

    computed: mapGetters({
      auth: 'auth/user'
    }),

    mounted() {
      this.user = Object.assign(this.user, this.auth)
    }
  }
</script>
