<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">
          {{ formTitle }} 
          <span v-if="mode=='duplicar'">{{ selectedItem.duplicado }}</span>
          <span v-if="mode=='renovar'">{{ fullName(selectedItem.persona) }}</span>
          </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form" v-model="valid" lazy-validation> 
          <v-text-field
            v-model="selectedItem.carnet"
            label="Numero de carnet de comercializador"
            :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            :error-messages="errorCarnetComercializador"
            @change="verificarCarnetComercializador()"
            :disabled="duplicar"
          ></v-text-field>
          <v-layout row wrap>
            <v-flex xs6>
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
                :disabled="duplicar||renovar"
              >                       
                <v-text-field
                  v-model="emision_date_formatted"
                  label="Fecha de emisión"
                  prepend-icon="event"
                  readonly
                  slot="activator"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  class="mr-2"
                  :disabled="duplicar||renovar"
                ></v-text-field>
                <v-date-picker v-model="emision_date" no-title @input="menu = false" locale="es-bo"></v-date-picker>
              </v-menu>
            </v-flex>
            <v-flex xs6>
              <v-menu
                v-model="menu2"
                :close-on-content-click="false"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                max-width="290px"
                min-width="290px"
                :disabled="duplicar||renovar"
              >                       
                <v-text-field
                  v-model="conclusion_date_formatted"
                  label="Fecha de conclusión"
                  prepend-icon="event"
                  readonly
                  slot="activator"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  class="mr-2"
                  :disabled="duplicar||renovar"
                ></v-text-field>
                <v-date-picker v-model="conclusion_date" no-title @input="menu2 = false" locale="es-bo"></v-date-picker>
              </v-menu>
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
  components: {},  
  props: ["item", "bus"], 
  data() {
    return {
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      //      
      menu: null,
      emision_date_formatted: null,
      emision_date: null,
      menu2: null,
      conclusion_date_formatted: null,
      conclusion_date: null,
      //
      mode: null,
      formTitle: 'Nuevo',
      duplicar: false,
      renovar: false,
      errorCarnetComercializador: null,
      personaId: null,
      persona: [],
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogCardForm", item => {      
      this.dialog = true      
      this.mode = item.mode      
      //
      if(this.mode == 'nuevo'){
        this.formTitle = 'Nuevo'
        this.personaId = item.id
        this.duplicar = false
        this.renovar = false
        this.emision_date_formatted = this.$moment().format("DD/MM/YYYY")
        this.conclusion_date_formatted = this.$moment().add(5, 'years').format("DD/MM/YYYY")
        this.selectedItem.fecha_emision = this.$moment().format("YYYY-MM-DD")
        this.selectedItem.fecha_conclusion = this.$moment().add(5, 'years').format("YYYY-MM-DD")
        this.selectedItem.carnet = item.carnet_identidad
      }
      if(this.mode == 'editar'){
        this.selectedIndex = item
        this.selectedItem = item
        this.formTitle = 'Editar'
        this.duplicar = false
        this.renovar = false
        this.emision_date_formatted = this.$moment(item.fecha_emision).format("DD/MM/YYYY")
        this.conclusion_date_formatted = this.$moment(item.fecha_conclusion).format("DD/MM/YYYY")
        
      }
      if(this.mode == 'duplicar'){
        this.selectedIndex = item
        this.selectedItem = item
        this.formTitle = 'Duplicado numero: '
        this.duplicar = true
        this.renovar = false
        this.emision_date_formatted = this.$moment(item.fecha_emision).format("DD/MM/YYYY")
        this.conclusion_date_formatted = this.$moment(item.fecha_conclusion).format("DD/MM/YYYY")
        this.getCarnetComercializador(item)
      }
      if(this.mode == 'renovar'){
        this.selectedIndex = item
        this.selectedItem = item
        this.formTitle = 'Renovar carnet de: '
        this.duplicar = false
        this.renovar = true
        this.selectedItem.fecha_emision = this.$moment().format("YYYY-MM-DD")
        this.emision_date_formatted = this.$moment(this.selectedItem.fecha_emision).format("DD/MM/YYYY")
        this.selectedItem.fecha_conclusion = this.$moment().add(5, 'years').format("YYYY-MM-DD")
        this.conclusion_date_formatted = this.$moment(this.selectedItem.fecha_conclusion).format("DD/MM/YYYY")
        this.selectedItem.duplicado = 0
      }
    }); 
  },
  computed: {
    
  },
  watch: {
    emision_date(val){
      this.selectedItem.fecha_emision = val
      this.emision_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
    conclusion_date(val){
      this.selectedItem.fecha_conclusion = val
      this.conclusion_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
  },
  methods: {    
    close() {
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.federacion = null
      this.central = null
      this.regional = null
      this.comunidad = null
      this.$refs.form.reset()
      this.bus.$emit("closeDialogCard");
      //
      this.mode = null
      this.formTitle = 'Nuevo'
      this.duplicar = null
      this.personaId = null
    },
    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form.validate()) {
          if (this.selectedIndex != -1) {
            if(this.mode == 'editar') {
              await axios.put("api/carnet_comercializador/"+this.selectedItem.id, this.selectedItem)
            }
            if(this.mode == 'duplicar') {
              await axios.put("api/carnet_comercializador/"+this.selectedItem.id, {duplicado: this.selectedItem.duplicado})
            }
            if(this.mode == 'renovar'){
              await axios.put("api/carnet_comercializador/"+this.selectedItem.id, {renovado: true})              
              await axios.post("api/carnet_comercializador", this.selectedItem)
            }
          } else {
            this.selectedItem.persona_id = this.personaId          
            await axios.post("api/carnet_comercializador", this.selectedItem)
          }
          
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async verificarCarnetComercializador() {
      let res = await axios.get("api/carnet_comercializador/fill/"+ JSON.stringify({'carnet': this.selectedItem.carnet}))
      if(res.data.length > 0) {
        this.errorCarnetComercializador = "Ya existe este carnet."
      } else {
        this.errorCarnetComercializador = null
      }
    },
    async getCarnetComercializador(item) {
      let res = await axios.get("api/carnet_comercializador/" + item.id)
      this.selectedItem.duplicado = res.data.duplicado + 1
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY")
    },
  },
};
</script>