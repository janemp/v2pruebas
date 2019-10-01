<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form" v-model="valid" lazy-validation>                
          <v-text-field
            v-model="selectedItem.resolucion"
            label="Resolución administrativa"
            :error-messages="errorResolucion"
            @change="verificarResolucion()"
            :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint                  
          ></v-text-field> 
                   
          <v-menu
            v-model="menu"
            :close-on-content-click="false"
            :nudge-right="40"
            lazy
            transition="scale-transition"
            offset-y
            full-width
            max-width="290px"
            min-width="290px"
          >                       
            <v-text-field
              v-model="fecha_date_formatted"
              label="Fecha de resolución administrativa"
              prepend-icon="event"
              readonly
              slot="activator"
              :rules="[v => !!v || 'Requerido']"
              hint="<span class='blue--text'>*Requerido</span>" persistent-hint
              class="mr-2"
            ></v-text-field>
            <v-date-picker v-model="fecha_date" no-title @input="menu = false" locale="es-bo"></v-date-picker>
          </v-menu>
          <v-autocomplete                      
            v-model="selectedItem.tipo_resolucion_id"
            :items="tiposResolucion"
            item-text="nombre"
            item-value="id"
            label="Tipo de resolución"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint>
          </v-autocomplete>
          <v-text-field
            v-model="selectedItem.numero_taques"
            label="Numero de taques"
            :rules="[v => !!v || 'Requerido', v => (v && v <= 100) || 'No mayor a 100']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            v-if="selectedItem.tipo_resolucion_id>=4"
          ></v-text-field>
          <v-textarea
            v-model="selectedItem.observaciones"
            label="Observaciones"
            row="2"                  
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
      tiposResolucion: [],
      errorResolucion: null,
      menu: null,
      fecha_date: null,
      fecha_date_formatted: null,
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
      fileAdjunto: null,
      valArchivo: null,
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogForm", item => {
      this.dialog = true       
      if(item.mode == "nuevo"){
        this.selectedItem.persona_id = item.id
      } else if(item.mode == "editar"){
        this.selectedItem = item
        this.selectedIndex = item          
        this.fecha_date_formatted = this.$moment().format("DD/MM/YYYY")
        this.valArchivo = "Cargado correctamente."
      }
    });
    this.getTiposResolucion()
    
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar'
    },
  },
  watch: {
    fecha_date(val){
      this.selectedItem.fecha_resolucion = val
      this.fecha_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.menu = null
      this.fecha_date = null
      this.fecha_date_formatted = null
      this.persona = []
      this.retenciones = []
      this.userValid = true
      this.fileAdjunto = null
      this.errorResolucion = null
      this.$refs.form.reset()
      this.bus.$emit("closeDialogForm")
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          this.selectedItem.user_id = this.$store.getters["auth/user"].id
          if (this.selectedIndex != -1) {
            await axios.put("api/resolucion_administrativa/"+this.selectedItem.id, this.selectedItem)
          } else {
            this.selectedItem.adjunto = this.fileAdjunto
            await axios.post("api/resolucion_administrativa", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async verificarResolucion() {
      let res = await axios.get("api/resolucion_administrativa/fill/"+ JSON.stringify({'resolucion': this.selectedItem.resolucion}))
      if(res.data.length > 0) {
        this.errorResolucion = null//"Ya existe esta resolución."
      } else {
        this.errorResolucion = null
      }
    },
    async getTiposResolucion() {
      let res = await axios.get("api/tipo_resolucion")
      this.tiposResolucion = res.data
    },
    // UPLOAD
    complete (file, status, xhr) {
      this.fileAdjunto = xhr.response
      this.valArchivo = "Cargado correctamente."
    },
      
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
  },  
};
</script>