<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Cesación temporal</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form" v-model="valid" lazy-validation>
            <v-layout row wrap>
            <v-flex xs6>
              <v-autocomplete                      
                v-model="selectedItem.cesacion_id"
                :items="cesaciones"
                item-text="nombre"
                item-value="id"
                label="Motivo de la cesación"
                :rules="[v => !!v || 'Requerido']"
                hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                class="mr-3"
                >
              </v-autocomplete>
            </v-flex>
            <v-flex xs6>
              <template>
              <vue-clip 
                  :options="options" 
                  :on-complete="complete">
                  <template slot="clip-uploader-action">                        
                  <div class="dz-message v-btn v-btn--small theme--light success">Subir archivo PDF</div>                        
                  </template>
                  <template slot="clip-uploader-body" slot-scope="props">                            
                  <div v-for="(file, index) in props.files" :key="index">
                      <v-icon color="success" v-if="file.status=='success'">check_circle</v-icon>
                      <span class="green--text" v-if="file.status=='success'"> correcto.</span>                            
                      <v-icon color="error" v-if="file.status=='error'">error</v-icon> 
                      <span class="red--text" v-if="file.status=='error'"> {{ file.errorMessage }}</span>
                  </div>
                  </template>                  
              </vue-clip>
              </template>
              <v-text-field
                v-model="valArchivo"
                readonly box
                label="Archivo PDF"
                :rules="[v => !!v || 'Requerido']"
                hint="<span class='blue--text'>*Requerido</span>" persistent-hint
              ></v-text-field>
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
import VueClip from 'vue-clip'

Vue.use(VueClip)

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
      cesaciones: [],
      options: {
        url: 'api/upload/file',
        headers: axios.defaults.headers.common,
        paramName: 'file',
        uploadMultiple: false,
        acceptedFiles: {
          extensions:  ['application/pdf'],
          message: 'Formato no permitido.'
        },
        maxFilesize: {
          limit: 5,
          message: 'El archivo exede los 5Mb.'
        },
        maxFiles: 1,
      },
      fileCesar: null,
      valArchivo: null,
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogCesar", item => {  
      this.selectedItem = item
      this.dialog = true
      this.selectedIndex = item
      this.getCesaciones()
    });   
  },
  computed: {
  },
  watch: {   
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.fileCesar = null
      this.valArchivo = null
      this.bus.$emit("closeDialog");
    },
    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form.validate()) {
          this.selectedItem.adjunto_cesacion = this.fileCesar
          this.selectedItem.persona_id = this.selectedItem.id
          let res = await axios.post("api/persona_cesacion", this.selectedItem)
          console.log(res.data)
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getCesaciones() {
      let res = await axios.get("api/cesacion/fill/" + JSON.stringify({'tipo_persona_id': this.selectedItem.tipo_persona_id}))
      this.cesaciones = res.data
    },
    
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    
    // UPLOAD
    complete (file, status, xhr) {
      this.fileCesar = xhr.response
      this.valArchivo = "Cargado correctamente."
    }
  },
};
</script>