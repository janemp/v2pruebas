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
          <v-autocomplete                      
            v-model="selectedItem.departamento_id"
            :items="departamentos"
            item-text="nombre"
            item-value="id"
            label="Departamento de producción"                        
            @change="getProvincias()"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido.</span>" persistent-hint
            :disabled="duplicar"
            >
          </v-autocomplete>
          <v-autocomplete                      
            v-model="selectedItem.provincia_id"
            :items="provincias"
            item-text="nombre"
            item-value="id"
            label="Provincia de producción" 
            @change="getMunicipios()"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido.</span>" persistent-hint
            :disabled="duplicar"
            >
          </v-autocomplete>
          <v-autocomplete                      
            v-model="selectedItem.municipio_id"
            :items="municipios"
            item-text="nombre"
            item-value="id"
            label="Municipio de producción"
            @change="getDependientes()"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido.</span>" persistent-hint
            :disabled="duplicar"
            >
          </v-autocomplete>        
          <v-combobox
            v-model="federacion"
            :items="federaciones"
            label="Federación"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido, Puede crear un nuevo registro.</span>" persistent-hint          
            :disabled="duplicar"
          ></v-combobox>
          <v-combobox
            v-model="central"
            :items="centrales"
            label="Central"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido, Puede crear un nuevo registro.</span>" persistent-hint          
            :disabled="duplicar"
          ></v-combobox>
          <v-combobox
            v-model="comunidad"
            :items="comunidades"
            label="Comunidad"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido, Puede crear un nuevo registro.</span>" persistent-hint
            :disabled="duplicar"
          ></v-combobox>
          <v-combobox
            v-model="regional"
            :items="regionales"
            label="Regional"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido, Puede crear un nuevo registro.</span>" persistent-hint
            :disabled="duplicar"
          ></v-combobox>
          <v-text-field
            v-model="selectedItem.codigo_socio"
            label="Codigo de socio"
            :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            :disabled="duplicar"
          ></v-text-field>
          <v-text-field
            v-model="selectedItem.codigo"
            label="Codigo"
            :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            :disabled="duplicar"
          ></v-text-field>
          <v-text-field
            v-model="selectedItem.carnet"
            label="Numero de carnet de productor"
            :rules="[v => !!v || 'Requerido', v => (v && v.length <= 30) || 'No mayor a 30 caracteres']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            :error-messages="errorCarnetProductor"
            @change="verificarCarnetProductor()"
            :disabled="duplicar"
          ></v-text-field>
          <v-text-field
            v-model="selectedItem.superficie"
            label="Superficie (m2.)"
            type="number"
            step="0.01"
            :rules="[v => !!v || 'Requerido', v => (v && v <= 100000) || 'Exede el limite']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
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
      personas: [],
      departamentos: [],
      provincias: [],
      municipios: [],
      federaciones: [],
      federacion: null,
      centrales: [],
      central: null,
      comunidades: [],
      comunidad: null,
      regionales: [],
      regional: null,
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
      errorCarnetProductor: null,
      personaId: null,
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
        this.personaId = item
        this.duplicar = false
        this.renovar = false
        this.emision_date_formatted = this.$moment().format("DD/MM/YYYY")
        this.conclusion_date_formatted = this.$moment().add(5, 'years').format("DD/MM/YYYY")
        this.selectedItem.fecha_emision = this.$moment().format("YYYY-MM-DD")
        this.selectedItem.fecha_conclusion = this.$moment().add(5, 'years').format("YYYY-MM-DD")
      }
      if(this.mode == 'editar'){
        this.selectedIndex = item
        this.selectedItem = item
        this.getProvincias()
        this.getMunicipios()
        this.getDependientes()
        this.federacion = item.federacion.nombre
        this.central = item.central.nombre
        this.regional = item.regional.nombre
        this.comunidad = item.comunidad.nombre
        this.formTitle = 'Editar'
        this.duplicar = false
        this.renovar = false
        this.emision_date_formatted = this.$moment(item.fecha_emision).format("DD/MM/YYYY")
        this.conclusion_date_formatted = this.$moment(item.fecha_conclusion).format("DD/MM/YYYY")
        
      }
      if(this.mode == 'duplicar'){
        this.selectedIndex = item
        this.selectedItem = item
        this.getProvincias()
        this.getMunicipios()
        this.getDependientes()
        this.federacion = item.federacion.nombre
        this.central = item.central.nombre
        this.regional = item.regional.nombre
        this.comunidad = item.comunidad.nombre
        this.formTitle = 'Duplicado numero: '
        this.duplicar = true
        this.renovar = false
        this.emision_date_formatted = this.$moment(item.fecha_emision).format("DD/MM/YYYY")
        this.conclusion_date_formatted = this.$moment(item.fecha_conclusion).format("DD/MM/YYYY")
        this.getCarnetProductor(item)
      }
      if(this.mode == 'renovar'){
        this.selectedIndex = item
        this.selectedItem = item
        this.getProvincias()
        this.getMunicipios()
        this.getDependientes()
        this.federacion = item.federacion.nombre
        this.central = item.central.nombre
        this.regional = item.regional.nombre
        this.comunidad = item.comunidad.nombre
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
    
    this.getPersonas()
    this.getDepartamentos()    
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
          let getFederacion = await axios.get("api/federacion/fill/"+ JSON.stringify({'nombre': this.federacion}))
          if(getFederacion.data.length > 0) {
            this.selectedItem.federacion_id = getFederacion.data[0].id
          } else {
            let setFederacion = await axios.post("api/federacion", {municipio_id: this.selectedItem.municipio_id, nombre: this.federacion})
            this.selectedItem.federacion_id = setFederacion.data.id
          }
          let getCentral = await axios.get("api/central/fill/"+ JSON.stringify({'nombre': this.central}))
          if(getCentral.data.length > 0) {
            this.selectedItem.central_id = getCentral.data[0].id
          } else {
            let setCentral = await axios.post("api/central", {municipio_id: this.selectedItem.municipio_id, nombre: this.central})
            this.selectedItem.central_id = setCentral.data.id
          }
          let getComunidad = await axios.get("api/comunidad/fill/"+ JSON.stringify({'nombre': this.comunidad}))
          if(getComunidad.data.length > 0) {
            this.selectedItem.comunidad_id = getComunidad.data[0].id
          } else {
            let setComunidad = await axios.post("api/comunidad", {municipio_id: this.selectedItem.municipio_id, nombre: this.comunidad})
            this.selectedItem.comunidad_id = setComunidad.data.id
          }
          let getRegional = await axios.get("api/regional/fill/"+ JSON.stringify({'nombre': this.regional}))
          if(getRegional.data.length > 0) {
            this.selectedItem.regional_id = getRegional.data[0].id
          } else {
            let setRegional = await axios.post("api/regional", {municipio_id: this.selectedItem.municipio_id, nombre: this.regional})
            this.selectedItem.regional_id = setRegional.data.id
          }
          if (this.selectedIndex != -1) {
            if(this.mode == 'editar') {
              await axios.put("api/carnet_productor/"+this.selectedItem.id, this.selectedItem)
            }
            if(this.mode == 'duplicar') {
              await axios.put("api/carnet_productor/"+this.selectedItem.id, {duplicado: this.selectedItem.duplicado})
            }
            if(this.mode == 'renovar'){
              await axios.put("api/carnet_productor/"+this.selectedItem.id, {renovado: true})              
              await axios.post("api/carnet_productor", this.selectedItem)
            }
          } else {
            this.selectedItem.persona_id = this.personaId          
            await axios.post("api/carnet_productor", this.selectedItem)
          }
          
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getPersonas() {
      let res = await axios.get("api/persona/fill/" + JSON.stringify({"tipo_persona_id": 1}))
      this.personas = res.data
    },
    async getDepartamentos() {
      let res = await axios.get("api/departamento")
      this.departamentos = res.data
    },
    async getProvincias() {
      let res = await axios.get("api/provincia/fill/" + JSON.stringify({"departamento_id": this.selectedItem.departamento_id}))
      this.provincias = res.data
    },
    async getMunicipios() {
      let res = await axios.get("api/municipio/fill/" + JSON.stringify({"provincia_id": this.selectedItem.provincia_id}))
      this.municipios = res.data
    },
    async getDependientes() {
      let res = await axios.get("api/federacion/fill/" + JSON.stringify({"municipio_id": this.selectedItem.municipio_id}))
      for(let federacion of res.data) {
        this.federaciones.push(federacion.nombre)
      }
      let res2 = await axios.get("api/central/fill/" + JSON.stringify({"municipio_id": this.selectedItem.municipio_id}))
      for(let central of res2.data) {
        this.centrales.push(central.nombre)
      }
      let res3 = await axios.get("api/comunidad/fill/" + JSON.stringify({"municipio_id": this.selectedItem.municipio_id}))
      for(let comunidad of res3.data) {
        this.comunidades.push(comunidad.nombre)
      }
      let res4 = await axios.get("api/regional/fill/" + JSON.stringify({"municipio_id": this.selectedItem.municipio_id}))
      for(let regional of res4.data) {
        this.regionales.push(regional.nombre)
      }
    },
    async verificarCarnetProductor() {
      let res = await axios.get("api/carnet_productor/fill/"+ JSON.stringify({'carnet': this.selectedItem.carnet}))
      if(res.data.length > 0) {
        this.errorCarnetProductor = "Ya existe este carnet."
      } else {
        this.errorCarnetProductor = null
      }
    },
    async getCarnetProductor(item) {
      let res = await axios.get("api/carnet_productor/" + item.id)
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