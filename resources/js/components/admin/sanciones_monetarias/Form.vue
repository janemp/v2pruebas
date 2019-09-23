<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <!-- <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="indigo">add_circle</v-icon>
      <span>Registrar Cumplimiento</span>
    </v-tooltip> -->
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
                  v-model="selectedItem.infraccion_id"
                  :items="infracciones"
                  item-text="nombre"
                  item-value="id"
                  label="Tipo de Infracción"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-select> 

                <v-select  
                  v-model="selectedItem.nombre"
                  :items="tiposSancion"
                  item-text="nombre"
                  item-value="nombre"
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
                  v-model="selectedItem.monto"
                  label="Monto"
                ></v-text-field>
                
                <v-text-field v-show="false"
                  v-model="selectedItem.estado"
                  label="Estado" v-text="1"            
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
      infracciones: [],
      error: '',
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
          message: 'El archivo excede los 5Mb.'
        },
        maxFiles: 1,
      },
      fileAdjunto: null,
      valArchivo: null,
    };
  },
  created() {},  
  mounted() {
    // console.log(this.selectedItem)
    // this.bus.$on("openDialogSancion", item => {
    //   console.log(item) 
    //   this.selectedItem = item;
    //   this.dialog = true;
    //   this.selectedIndex = item;     
    //   this.selectedItem.comercializador_id= item.comercializador_id;
    // });

    this.bus.$on("openDialogForm1", item => {
      this.selectedItem.persona_id= item;
      this.dialog = true;
    });

    this.bus.$on("openDialogForm", item => {
      this.selectedItem.persona_id= item;
      this.selectedItem= item;
       this.selectedIndex = item  
      this.dialog = true;
    });

    this.getTiposSancion()
    this.getInfracciones()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
      console.log(this.selectedIndex)
      //return this.selectedItem.mode === 'Nuevo' ? 'Nuevo ' : 'Editar '
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

    //  async getTiposInfraccion() {
    //   let res = await axios.get("api/tipo_infraccion")
    //   this.tiposInfraccion = res.data
    // },
     
    complete (file, status, xhr) {
      this.fileAdjunto = xhr.response
      this.valArchivo = "Cargado correctamente."
    },

    close() {
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialogForm");
      this.selectedIndex = -1;
      this.selectedItem = {};
      this.fileAdjunto = null;

    },
    async save() {
      try {
        if (this.$refs.form.validate()) { 
          // console.log(this.selectedItem)
          // let res = await axios.get('api/sancion', this.selectedItem)

     
          if (this.selectedItem.mode == 'Editar') {
            await axios.put("api/sancion/"+this.selectedItem.id, this.selectedItem)
          } 
          else {
            this.selectedItem.adjunto = this.fileAdjunto
            this.selectedItem.estado = 1
            await axios.post("api/sancion", this.selectedItem)
            // var data = {
            // 'sancion_id': this.sancion_id,
            // 'persona_id': this.selectedItem.persona_id
            // }
            // console.log(data)
            // await axios.post('api/comercializador_infraccion', data)  
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