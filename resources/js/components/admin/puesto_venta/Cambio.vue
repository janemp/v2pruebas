<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form1" v-model="valid" lazy-validation>
            <v-layout row wrap>
                <v-flex xs6>
                <v-textarea
                    v-model="selectedCambia.motivo_cambio"
                    label="Motivo del cambio"
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
                <v-text-field
                    v-model="adjuntos"
                    readonly box
                    label="Archivo PDF"
                    :rules="[v => !!v || 'Requerido', v => (v && v.length <= 64) || 'No mayor a 64 caracteres']"
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

export default {
  components: {
    
  },  
  props: ["item", "bus"], 
  data() {
    return {
      step: 1,
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      selectedCambia: {},
      selectedPuesto: {},
      cambia: false,
      idOld: null,
      adjuntos: null,
      comercializadores: [],
      comercializador: [],      
      headers: [
            {
              text: "Nombre",
              value: "primer_apellido",
              align: "center"
            },
            {
              text: "Carnet de identidad",
              value: "carnet_identidad",
              align: "center"
            },
            {
              text: "Telefono",
              value: "telefono",
              align: "center"
            },
            {
              text: "Email",
              value: "email",
              align: "center"
            },
            {
              text: "Estado",
              align: "center",
              sortable: false,
            },
          ],
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
      municipios: []
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on('openDialogCambiar', item => {
        this.dialog = true    
        this.selectedIndex = item
        this.selectedItem = item
    })
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar'
    },
  },
  watch: {
    
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.selectedCambia = {} 
      this.cambia = false  
      this.adjuntos = null
      this.bus.$emit("closeDialogCambiar")
    },
    async save() {
      try {
        if (this.$refs.form1.validate()) {
            var data = {
                'user_id': this.$store.getters["auth/user"].id,
                'persona_puesto_venta_id': this.selectedItem.id,
                'motivo_cambio': this.selectedCambia.motivo_cambio,
                'adjunto': this.selectedCambia.adjunto
            }
            let res = await axios.post('api/autorizacion_cpv', data)
            await axios.put('api/puesto_venta/'+this.selectedItem.puesto_venta.id, {'cambiado': true})
            this.$toast.success('Correcto.')
            this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getComercializadores() {
      var param = {'tipo_persona_id': 4}
      let res = await axios.get("api/persona/fill/"+JSON.stringify(param))
      this.comercializadores = res.data
    },
    async getComercializador() {
      let res = await axios.get("api/persona/fill/" + JSON.stringify({'id': this.selectedItem.persona_id}))
      this.comercializador = res.data
    },
    async getMunicipios(){
      let res = await axios.get('api/municipio')
      this.municipios = res.data
    },
    // UPLOAD
    complete (file, status, xhr) {
      this.selectedCambia.adjunto = xhr.response
      this.adjuntos = 'Cargado correctamente.'
    },
    nextStep() {
      switch (this.step) {
        case 1:
          if(this.$refs.form1.validate()) {           
            this.step = 2            
          }
          break
        // case 2:
        //   if(this.$refs.form2.validate() && this.cambia) {           
        //     this.step = 3           
        //   }
        //   break
      }
    },
    previousStep() {
      switch (this.step) { 
        // case 3:          
        //   this.step = 2         
        //   break
        case 2:          
          this.step = 1          
          break       
      }
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