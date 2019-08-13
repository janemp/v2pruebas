<template>
  <v-card raised>
    <v-toolbar dark color="primary" flat>
      <v-toolbar-title>Password reset</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <div class="fullwidth">
        <p>Porfavor ingrese su email. Recivira un correo para recuperar su contraseña.</p>
        <password-forgot-form @success="success"></password-forgot-form>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    components: {
      'password-forgot-form': require('./PasswordForgotForm')
    },

    methods: {
      success(data) {
        this.$router.push({ name: 'login' })
      }
    }
  }
</script>
