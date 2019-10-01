<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">    
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Retener</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form" v-model="valid" lazy-validation>      
          <!-- <v-text-field
            v-model="selectedItem.correlativo"
            label="Número"
            readonly
            box
          ></v-text-field> -->
          <v-autocomplete                      
            v-model="selectedItem.causas"
            :items="causasRetencion"
            item-text="nombre"
            item-value="id"
            label="Causa de retención"
            multiple small-chips
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint    
          ></v-autocomplete>
          <v-text-field
            v-model="selectedItem.inicial_embalaje"
            label="Iniciales de embalaje"
            :rules="[ v => (!v)?true:((v.length <= 15)?true:'No mayor a 15 caracteres')]"                  
          ></v-text-field>
          <v-autocomplete                      
            v-model="selectedItem.departamento_id"
            :items="departamentos"
            item-text="nombre"
            item-value="id"
            label="Departamento"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            @change="getProvincias()"
          ></v-autocomplete>
          <v-autocomplete                      
            v-model="selectedItem.provincia_id"
            :items="provincias"
            item-text="nombre"
            item-value="id"
            label="Provincias"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            @change="getMunicipios()"
          ></v-autocomplete>
          <v-autocomplete                      
            v-model="selectedItem.municipio_id"
            :items="municipios"
            item-text="nombre"
            item-value="id"
            label="Municipios"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint    
          ></v-autocomplete>
          <v-autocomplete                      
            v-model="selectedItem.localidad_id"
            :items="localidades"
            item-text="nombre"
            item-value="id"
            label="Localidad"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint    
          ></v-autocomplete>
          <v-text-field
            v-model="selectedItem.lugar"
            label="Lugar"
            :rules="[v => !!v || 'Requerido', v => (v && v.length <= 100) || 'No mayor a 100']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
          ></v-text-field>
          <v-textarea
            v-model="selectedItem.descripcion"
            label="Descripción"
            rows="2"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
          ></v-textarea>
          <v-text-field
            v-model="selectedItem.numero_taques"
            label="Numero de taques"
            :rules="[v => !!v || 'Requerido', v => (v && v <= 100) || 'No mayor a 100']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            @input="getLibras()"
          ></v-text-field>
          <v-text-field
            v-model="selectedItem.libras"
            label="Libras"
            :rules="[v => !!v || 'Requerido', v => (v && v <= 9999) || 'No mayor a 9999']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint                  
          ></v-text-field>
          <v-layout row wrap>
            <v-flex xs4>
              <v-text-field
                v-model="selectedItem.numero_placa"
                label="Placa de vehículo"
                :rules="[ v => (!v)?true:((v.length <= 15)?true:'No mayor a 15 caracteres')]"
                class="mr-3"
              ></v-text-field>
            </v-flex>
            <v-flex xs4>
              <v-autocomplete                      
                v-model="selectedItem.marca_id"
                :items="marcas"
                item-text="nombre"
                item-value="id"
                label="Marca de vehículo"
                class="mr-3"
              ></v-autocomplete>
            </v-flex>
            <v-flex xs4>
              <v-autocomplete                      
                v-model="selectedItem.vehiculo_id"
                :items="vehiculos"
                item-text="nombre"
                item-value="id"
                label="Tipo de vehículo"
              ></v-autocomplete>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs4>
              <v-text-field
                v-model="selectedItem.numero_vuelo"
                label="Numero de vuelo"
                :rules="[ v => (!v)?true:((v.length <= 15)?true:'No mayor a 15 caracteres')]"
                class="mr-3"
              ></v-text-field>
            </v-flex>
            <v-flex xs4>
              <v-text-field
                v-model="selectedItem.empresa_vuelo"
                label="Empresa de vuelo"
                :rules="[ v => (!v)?true:((v.length <= 30)?true:'No mayor a 30 caracteres')]"
                class="mr-3"
              ></v-text-field>
            </v-flex>
            <v-flex xs4>
              <v-text-field
                v-model="selectedItem.otros_vuelo"
                label="Otros datos de vuelo"
                :rules="[ v => (!v)?true:((v.length <= 100)?true:'No mayor a 100 caracteres')]"
              ></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs4>
              <v-text-field
                v-model="selectedItem.nombre_embarcacion"
                label="Nombre de embarcación"
                :rules="[ v => (!v)?true:((v.length <= 100)?true:'No mayor a 100 caracteres')]"
                class="mr-3"
              ></v-text-field>
            </v-flex>
            <v-flex xs4>
              <v-autocomplete                      
                v-model="selectedItem.embarcacion_id"
                :items="embarcaciones"
                item-text="nombre"
                item-value="id"
                label="Tipo de embarcación"
                class="mr-3"
              ></v-autocomplete>
            </v-flex>
            <v-flex xs4>
              <v-text-field
                v-model="selectedItem.numero_embarcacion"
                label="Numero de embarcación"
                :rules="[ v => (!v)?true:((v.length <= 100)?true:'No mayor a 100 caracteres')]"                  
              ></v-text-field>
            </v-flex>
          </v-layout>
          <v-radio-group 
            v-model="selectedItem.responsable" row 
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint>
            <v-radio label="GECC" value="GECC" color="green"></v-radio>
            <v-radio label="DIGCOIN" value="DIGCOIN" color="green"></v-radio>
          </v-radio-group>
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
      causasRetencion: [],
      departamentos: [],
      provincias: [],
      municipios: [],
      localidades: [],
      vehiculos: [],
      marcas: [],
      embarcaciones: [],
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogRetener", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.getCorrelativo()
      this.getCausasRetencion()
      this.getDepartamentos()
      this.getLocalidades()
      this.getVehiculos()
      this.getMarcas()
      this.getEmbarcaciones()
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
      this.causasRetencion = []
      this.departamentosRetencion = []
      this.provincias = []
      this.municipios = []
      this.localidades = []
      this.vehiculos = []
      this.marcas = []
      this.embarcaciones = []      
      this.$refs.form.reset()
      this.bus.$emit("closeDialog")
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          this.selectedItem.user_id = this.$store.getters["auth/user"].id
          this.selectedItem.hoja_ruta_id = this.selectedItem.id
          let res = await axios.post("api/retencion", this.selectedItem)
          await axios.put("api/hoja_ruta/" + this.selectedItem.id, {retenido: true})
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getCorrelativo() {
      let res = await axios.get("api/retencion/max/correlativo")
      this.selectedItem.correlativo = res.data
    },
    async getCausasRetencion() {
      let res = await axios.get("api/causa_retencion")
      this.causasRetencion = res.data
    },
    async getDepartamentos() {
      let res = await axios.get("api/departamento")
      this.departamentos = res.data
    },
    async getProvincias() {
      let res = await axios.get("api/provincia")
      this.provincias = res.data
    },
    async getMunicipios() {
      let res = await axios.get("api/municipio")
      this.municipios = res.data
    },
    async getLocalidades() {
      let res = await axios.get("api/localidad")
      this.localidades = res.data
    },
    async getVehiculos() {
      let res = await axios.get("api/vehiculo")
      this.vehiculos = res.data
    },
    async getMarcas() {
      let res = await axios.get("api/marca")
      this.marcas = res.data
    },
    async getEmbarcaciones() {
      let res = await axios.get("api/embarcacion")
      this.embarcaciones = res.data
    },
    getLibras(){
        this.selectedItem.libras = this.selectedItem.numero_taques * 50 
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