<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">Puestos de control</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
          <v-data-table
              :headers="headers"
              :items="puestosControl"
              :rows-per-page-items="[5,10,20,{text:'TODO',value:-1}]"
              disable-initial-sort
              class="elevation-1"
              v-if="puestosControl">
              <template slot="items" slot-scope="props">
                <tr>
                  <td class="text-xs-center"> {{ props.item.nombre }} </td>
                  <td class="text-xs-center"> {{ props.item.direccion }} </td>
                  <td class="text-xs-center"> 
                    <!-- <v-switch
                      v-model="props.item.visitado"
                      :disabled="props.item.visitado"
                      @change="addPuesto(props.item)"
                    ></v-switch> -->
                    <v-switch
                      v-model="props.item.visitado"
                      disabled
                    ></v-switch>
                  </td>
                </tr>
              </template>
              <template slot="no-data">
                <v-alert slot="no-results" :value="true" color="info" icon="fa fa-times">
                  Sin Puestos de control.
                </v-alert>
              </template>
            </v-data-table>        
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>        
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cerrar</v-btn>
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
      puestosControl: [],
      sw: false,
      headers: [
          {
            text: "Nombre",
            value: "nombre",
            align: "center"
          },
          {
            text: "Dirección",
            value: "direccion",
            align: "center"
          },
          {
            text: "estado",
            align: "left",
            sortable: false
          },      
        ],
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogControl", item => {  
      this.selectedItem = item
      this.dialog = true
      this.selectedIndex = item
      this.verificaPuesto()
    });   
  },
  computed: {
  },
  watch: {   
  },
  methods: {    
    close() {
      // this.terminado(this.selectedItem, this.puestosControl)
      this.bus.$emit("closeDialogControl1", this.selectedItem)
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.selectedControl = {}
    },
    async refresh(){
      var param = {'id': this.selectedItem.id}
      let res = await axios.get('api/control_hoja_ruta/fill/'+JSON.stringify(param))
      this.selectedItem = res.data
      this.verificaPuesto()
    },
    async printItem(item) {
      try {        
        let res = await axios({
          method: "GET",
          url: "api/guia_internacion/print/" + item.id,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {type: "application/pdf"});
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    async verificaPuesto(){
        for(var i=0; i<this.selectedItem.hoja_ruta.ruta.puestos_de_control.length; i++){
          var param = {"control_id": this.selectedItem.id, "puesto_id": this.selectedItem.hoja_ruta.ruta.puestos_de_control[i].id}
          let res = await axios.get('api/control_puesto/fill/'+JSON.stringify(param));
          if(res.data.length > 0){
            this.selectedItem.hoja_ruta.ruta.puestos_de_control[i].visitado = true
          }
          else{
            this.selectedItem.hoja_ruta.ruta.puestos_de_control[i].visitado = false
          }
        }
        this.puestosControl = this.selectedItem.hoja_ruta.ruta.puestos_de_control
        
    },
    async terminado(control_hoja_ruta, puestos_de_control){
      var sw = false;
      var datos = [];
      for(var i=0; i<puestos_de_control.length; i++){
        var param = {"control_id": control_hoja_ruta.id, "puesto_id": puestos_de_control[i].id}
        let res = await axios.get('api/control_puesto/fill/'+JSON.stringify(param));
        if(res.data.length > 0){
          datos.push(true);
        }
        if(datos.length >= puestos_de_control.length){
          await axios.put('api/hoja_ruta/'+control_hoja_ruta.hoja_ruta.id, {'estado': true})
          //this.close();
          this.bus.$emit("closeDialogRefresh", this.selectedItem)
          this.dialog = false;
          this.selectedIndex = -1
          this.selectedItem = {}
          this.selectedControl = {}
        }
      }
    }
  },
};
</script>