<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="indigo">add_circle</v-icon>
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
                  v-model="selectedItem.codigo"
                  label="Codigo"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 5) || 'No mayor a 5 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  :error-messages="error"
                  @change="codevalidate()"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.nombre"
                  label="Nombre"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 50) || 'No mayor a 50 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card-text>  
                <v-text-field
                  v-model="selectedItem.color"
                  label="Color"
                  type="color"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 50) || 'No mayor a 50 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>                      
                <v-textarea
                  v-model="selectedItem.descripcion"
                  label="Descripción"
                  rows="1"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 50) || 'No mayor a 100 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-textarea>                
              </v-card-text>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
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
      error: ''
    };
  },
  created() {},  
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;
    });
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
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
            await axios.put("api/zona_autorizada/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/zona_autorizada", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async codevalidate(){
      var params = {'codigo': this.selectedItem.codigo}
      let res = await axios.get('api/zona_autorizada/show_fill/'+JSON.stringify(params))
      if(res.data.length > 0){
        this.error = 'El codigo ya existe'
      }
      else{
        this.error = ''
      }
    }
  },  
};
</script>