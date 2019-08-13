<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-card-text>                
                <v-text-field
                  v-model="selectedItem.user_name"
                  label="Nombre de usuario"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.email"
                  label="Email"
                  :rules="[v => !!v || 'Requerido', v => /.+@.+/.test(v) || 'E-mail no es valido']"
                  :error-messages="validEmail"
                  @change="checkEmail()"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-if="selectedIndex==-1"
                  v-model="selectedItem.password"
                  :value="selectedItem.password"
                  :append-icon="show1 ? 'visibility' : 'visibility_off'"                  
                  :type="show1 ? 'text' : 'password'"
                  label="Contraseña"
                  hint="<span class='blue--text'>* La contraseña es el email</span>" persistent-hint
                  counter
                  @click:append="show1 = !show1"
                  box
                  readonly
                ></v-text-field>
                <v-select
                  v-model="selectedItem.roles"
                  :items="roles"
                  item-text="name"
                  item-value="id"
                  chips
                  label="Roles"
                  multiple
                  :rules="[v => !!v || 'Requerido', v => (v && v.length > 0) || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-select>
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card-text>                
                <v-text-field
                    v-model="selectedItem.name"
                    label="Nombre"
                    :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
                    hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                    v-model="selectedItem.last_name"
                    label="Primer apellido"
                    :rules="[ v => (!v)?true:((v.length <= 30)?true:'No mayor a 30 caracteres')]"
                ></v-text-field>
                <v-text-field
                    v-model="selectedItem.mothers_last_name"
                    label="Segundo apellido"
                    :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
                    hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
              </v-card-text>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="info" v-if="selectedIndex!=-1" small @click="recovery()" ><v-icon>build</v-icon> Restablecer contraseña</v-btn>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'

export default {
  props: ["item", "bus"],  
  data() {
    return {      
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      validEmail: null,
      roles: [],
      show1: false,
    };
  },
  created() {},  
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      var rols = []
      for(var rol of this.selectedItem.roles) {
        rols.push(rol.id) 
      }
      this.selectedItem.roles = []
      this.selectedItem.roles = rols
      this.dialog = true;
      this.selectedIndex = item;
    });
    this.getRoles()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
    async getRoles() {
      let res = await axios.get("api/role")
      this.roles = res.data
    },
    async checkEmail(){
      let res = await axios.get("api/user")
      res = res.data.filter(obj => { return obj.email == this.selectedItem.email; });
      this.selectedItem.password = this.selectedItem.email
      if(res.length > 0) {
        this.validEmail = "Este email ya existe."
      } else {
        this.validEmail = null
      }
    },
    close() {
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialog");
      this.selectedIndex = -1;
      this.selectedItem = {}
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          if (this.selectedIndex != -1) {
            await axios.put("api/user/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/user", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async recovery() {
      try {
        await axios.put("api/updatepass/"+this.selectedItem.id, {'password': this.selectedItem.email})
        this.$toast.success('Correcto!, Su contraseña es su email.')
        this.close();
      } catch(e) {
        console.log(e)
      }
    }
  },  
};
</script>