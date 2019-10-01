<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Verficación de Autorización de Renovación</v-toolbar-title>
      </v-toolbar>
      <v-card-text>  
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-card-text>  
                <v-autocomplete  
                  v-model="selectedItem.region_id"
                  :items="regiones" 
                  item-text="nombre"
                  item-value="id"
                  label="Region"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-autocomplete>   

                <v-text-field 
                  v-model="selectedItem.codigo_catastral"
                  label="Codigo"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 5) || 'No mayor a 5 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>

                <v-text-field 
                  v-model="selectedItem.latitud"
                  label="Latitud"
                  type="number"
                ></v-text-field>
                
                <v-text-field aria-disabled="true"
                  v-model="selectedItem.longitud"
                  label="Longitud"  
                  type="number"          
                ></v-text-field>  
                
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card-text>  
                 <v-select  
                  v-model="selectedItem.motivo_actualizacion_id"
                  :items="motivos"
                  item-text="nombre"
                  item-value="id"
                  label="Motivo de Actualización"
                ></v-select>
                              
                <v-textarea
                  v-model="selectedItem.observaciones"
                  label="Observaciones"
                ></v-textarea>  
                    <template>
                      <v-card flat color="blue-grey lighten-5">
                        <v-card-text >
                              <table class="v-table theme--light">                      
                                <tr>
                                  <th class="text-xs-left">Adjunto: </th>
                                  <td class="text-xs-left">
                                    <v-btn icon :href="selectedItem.informe_adjunto" target="_blank">
                                      <v-icon color="red">picture_as_pdf</v-icon>
                                    </v-btn>
                                  </td>
                                </tr>
                              </table>
                          </v-card-text>
                      </v-card>
                    </template>           
              </v-card-text>
            </v-flex>
          </v-layout>
        </v-form>     
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
         <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <!-- <v-btn color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn> -->
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
      step: 1,
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      regiones: [],
      motivos: [],
      parcela: [],
      table: [], 
      table2: [],      
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
  created() {    
  }, 

  mounted() {
    // this.bus.$on("openDialog", item => {   
    //   this.selectedItem.persona_id= item;
    //   this.selectedItem= item;
    //   this.selectedIndex = item  
    //   this.dialog = true;
    // });

    // this.bus.$on("openDialogForm", item  => {
    //   this.idpersona= item.id
    //   this.dialog = true
    //   this.selectedIndex=item
    //   this.selectedItem = item       
    //   this.getTable();
    // });

    this.bus.$on("openDialogVer", item  => {
      this.idpersona= item.id
      this.dialog = true
      this.selectedItem = item 
      this.selectedIndex = item
      this.getTableVerificacion();
    });

    this.getRegiones()
    this.getMotivosActualizacion()
  },
  computed: {
    // formTitle() {
    //   return this.selectedIndex === -1 ? 'Registro de Autorización de Renovación ' : 'V de Autorización de Renovación '//'Verificación de Autorización de Renovación '
    // },
  },
  watch: {      
  },

  methods: {     

    close() {
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialogVer");
      this.selectedIndex = -1;
      this.selectedItem = {};
      this.fileAdjunto = null;
      this.table=[];
      this.table2=[];
    },   

    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form.validate()) {
          if (this.selectedItem.mode == 'Editar') {
            //await axios.put("api/parcela/"+this.selectedItem.id, this.selectedItem)
          } 
          else {          
            var data = {
            'informe_adjunto': this.fileAdjunto,
            'observaciones': this.selectedItem.observaciones,
            'verificacion_destruccion' : false
            }
            
            await axios.post('api/autorizacionrenovacion', data)              
            await axios.put("api/parcela/edit/"+this.selectedItem.id, this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }        
      } catch(e) {
        console.log(e)
      }
    },
  
    async getRegiones() {
      let res = await axios.get("api/regional")
      this.regiones = res.data
    },

    async getMotivosActualizacion() {
      let res = await axios.get("api/motivoactualizacion")
      this.motivos = res.data
    },


    // async getTable() {
    //   try {                
    //     let res = await axios.get("api/parcela/showfill/" + this.selectedItem.id )
    //     this.table = res.data[0];
    //     this.selectedItem= this.table;
    //     console.log(this.selectedItem)
    //   } catch (e) {
    //     console.log(e);
    //   }    
    // },
    
     async getTableVerificacion() {
      try {                
        let res = await axios.get("api/parcela/showfillver/" + this.selectedItem.id )
        this.table2 = res.data[0];
        this.selectedItem= this.table2;
      } catch (e) {
        console.log(e);
      }    
    },

    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },

    complete (file, status, xhr) {
      this.fileAdjunto = xhr.response
      this.valArchivo = "Cargado correctamente."
    },

  },
};
</script>