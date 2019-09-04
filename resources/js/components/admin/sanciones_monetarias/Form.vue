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
                <v-select  
                  v-model="selectedItem.tipo_sancion_id"
                  :items="tiposSancion"
                  item-text="nombre"
                  item-value="id"
                  label="Tipo de Sanción"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint

                ></v-select>
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
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 100) || 'No mayor a 100 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card-text>                
                <v-textarea
                  v-model="selectedItem.descripcion"
                  label="Descripción"
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
  components: {
    
  },  
  props: ["item", "bus"],  
  data() {
    return {      
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      tiposSancion: [],
      error: ''
    };
  },
  created() {},  
  mounted() {
    this.bus.$on("openDialogForm", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;      
    });
    this.getTiposSancion()
   
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
    async getInfracciones() {
      let res = await axios.get("api/infraccion")
      this.infracciones = res.data
    },

    async getTiposSancion() {
      let res = await axios.get("api/tipo_sancion")
      this.tiposSancion = res.data
    },

    close() {
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialogForm");
      this.selectedIndex = -1;
      this.selectedItem = {}
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          if (this.selectedIndex != -1) {
            await axios.put("api/sancion/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/sancion", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    codevalidate(){
      axios.get('/api/sancion/codigo/'+this.selectedItem.codigo).then(response =>{
        this.error = response.data;
      });
    }
  },  
};
</script>