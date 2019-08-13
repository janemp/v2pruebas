<template>
  <v-dialog persistent v-model="dialog" max-width="400px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Iniciar control</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        Esta a punto de guardar el puesto de control como visitado, esta acción es irreversible y no podra editar ni cambiar a futuro.
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>        
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Marcar Puesto de control</v-btn>
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
      selectedControl: {},
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
      fileCesar: null,
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogGuarda", item => {  
      this.selectedItem = item
      this.dialog = true
      this.selectedIndex = item
    });    
  },
  computed: {
  },
  watch: {   
  },
  methods: {    
    close() {
      this.bus.$emit("closeDialogGuarda", this.selectedItem)
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.selectedControl = {}
      this.fileCesar = null
    },
    async save() {
      try {        
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        let res = await axios.post("api/control_puesto", this.selectedItem)   
        this.$toast.success('Correcto.')
        //this.printItem(this.selectedItem)
        this.selectedItem = true
        this.close();
      } catch(e) {
        console.log(e)
      }
    },
    cancelar(){
      this.selectedItem = false
      this.close()
    }
  },
};
</script>