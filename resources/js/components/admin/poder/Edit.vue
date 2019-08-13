<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout wrap>
            <v-flex xs6>
                <v-textarea
                    v-model="selectedItem.motivo"
                    label="Motivo de la conseción de poder"
                    :rules="[v => !!v || 'Requerido']"
                    hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                    class="mr-2"
                ></v-textarea>
            </v-flex>
            <v-flex xs6>
                <template>
                <vue-clip 
                    :options="options" 
                    :on-complete="complete">
                    <template slot="clip-uploader-action">                        
                    <div class="dz-message v-btn v-btn--small theme--light info">Subir archivo PDF</div>                        
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
      error: '',
      options: {
        url: 'api/upload',
        headers: axios.defaults.headers.common,
        paramName: 'file',
        uploadMultiple: false,
        acceptedFiles: {
          extensions:  ['application/pdf'],
          message: 'Formato no permitido.'
        },
        maxFiles: 1,
      },
      fileMotivo: ''
    };
  },
  created() {},  
  mounted() {
    this.bus.$on("openDialogEdit", item => {
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
            this.selectedItem.adjuntos = this.fileMotivo;
            await axios.put("api/poder/"+this.selectedItem.id, this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    complete (file, status, xhr) {
      this.fileMotivo = xhr.response
    }
  },  
};
</script>