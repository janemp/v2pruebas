<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form1" v-model="valid" lazy-validation>
          <v-text-field
            v-model="selectedItem.destino"
            label="Destino"
            :rules="[v => !!v || 'Requerido', v => (v && v.length <= 100) || 'No mayor a 100 caracteres']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
          ></v-text-field>
        </v-form>                       
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" small :disabled="!valid" @click="save()"><v-icon>check</v-icon> Guardar</v-btn>
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
      comercializadores: [],
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogDonar", item => {
      this.dialog = true      
      this.selectedItem = item
      this.begin_date_formatted = this.$moment(item.fecha_inicio).format("DD/MM/YYYY")
      this.end_date_formatted = this.$moment(item.fecha_final).format("DD/MM/YYYY")
    });
    this.bus.$on('closeDialogIniciar', () => {
      this.close();
    });
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar'
    },
  },
  watch: {
    begin_date(val){
      this.selectedItem.fecha_inicio = val
      this.begin_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
    end_date(val){
      this.selectedItem.fecha_final = val
      this.end_date_formatted = this.$moment(val).format("DD/MM/YYYY") || this.$moment().format("DD/MM/YYYY");
    },
  },
  methods: {    
    close() {
      this.comercializador = null
      this.verifica = false;
      this.encontrado = {};
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}      
      this.step = 1
      this.$refs.form1.reset()
      this.bus.$emit("closeDialogDonar")
    },
    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form1.validate()) {          
          if (this.selectedIndex != -1) {
            await axios.put('api/resolucion_administrativa/'+this.selectedItem.resolucion_administrativa_id, {'activo': false})
            console.log(this.selectedItem)
            await axios.post("api/donacion", this.selectedItem)
          } else {
            await axios.put('api/resolucion_administrativa/'+this.selectedItem.resolucion_administrativa_id, {'activo': false})
            console.log(this.selectedItem)
            await axios.post("api/donacion", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },    
  },  
};
</script>