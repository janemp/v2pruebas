<template>
  <v-dialog persistent v-model="dialog" max-width="500px" @keydown.esc="close">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <Guarda :bus="bus"/>
      <v-card-text>
        <v-flex>
          <p class="error">{{ error }}</p>
          <p class="decode-result"><b>Enfoque con la cámara el codigo QR de la hoja de ruta correspondiente</b></p>
          <qrcode-stream v-if="dialog && habilitado==true" @decode="onDecode" @init="onInit" />
          <span v-if="habilitado == false">Ya se controló esta hoja de ruta por el funcionario {{usuario.name}} {{usuario.last_name}} {{usuario.mothers_last_name}} </span>
        </v-flex>
        <v-flex v-if="control_hoja_ruta">
          <v-card>
            <v-flex>
              <p>Correlativo de la hoja de ruta: {{control_hoja_ruta.hoja_ruta.correlativo}}</p>
              <p>Carnet de identidad de la persona: {{control_hoja_ruta.hoja_ruta.persona.carnet_identidad}}</p>
              <p>Nombre de la persona: {{control_hoja_ruta.hoja_ruta.persona.nombre}} {{control_hoja_ruta.hoja_ruta.persona.primer_apellido}} {{control_hoja_ruta.hoja_ruta.persona.segundo_apellido}}</p>
              <p>Puesto de control: {{control_hoja_ruta.puesto.nombre}}</p>
              <p>Dirección puesto de control: {{control_hoja_ruta.puesto.direccion}}</p>
            </v-flex>
          </v-card>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="success" v-if="!permiso" small @click.native="guarda(control_hoja_ruta)"><v-icon>check</v-icon> Marcar como visitado</v-btn>
            <v-btn color="error" v-if="permiso" small><v-icon>close</v-icon> Ya se visitó el puesto de control</v-btn>
          </v-card-actions>
        </v-flex>
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
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
import Guarda from './Guarda'

export default {
  components: {
    QrcodeStream,
    QrcodeDropZone,
    QrcodeCapture,
    Guarda
  },  
  props: ["item", "bus"], 
  data() {
    return {
      result: '',
      error: '',
      selectedIndex: -1,
      selectedItem: {},
      dialog: false,
      control_hoja_ruta: null,
      permiso: true,
      habilitado: true,
      usuario: null
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialogQr", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.habilitar()
    });
    this.bus.$on("closeDialogGuarda", item => {
      this.terminado(this.control_hoja_ruta, this.control_hoja_ruta.hoja_ruta.ruta.puestos_de_control)
      this.close();
    });
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
      this.bus.$emit("closeDialogQr")
      this.control_hoja_ruta = null
    },
    guarda(control){
      var data = {
        'control_id': control.id,
        'puesto_id': control.puesto.id,
        'user_id': this.$store.getters["auth/user"].id
      }
      this.bus.$emit('openDialogGuarda', data)
    },
    async save() {
      try {
        if (this.$refs.form2.validate()) {
          this.selectedItem.user_id = this.$store.getters["auth/user"].id
          if (this.selectedIndex != -1) {
            await axios.put("api/guia_internacion/"+this.selectedItem.id, this.selectedItem)
          } else {
            this.selectedItem.id_guia_internacion = this.selectedItem.correlativo
            await axios.post("api/guia_internacion", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    nextStep() {
      switch (this.step) {
        case 1:
          if(this.$refs.form1.validate()) {           
            this.step = 2            
          }
          break        
      }
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    formatDate(date){
      return this.$moment(date).format("DD/MM/YYYY ")
    },
    onDecode (result) {
        // this.result = result
        this.getControlHojaRuta(result)
    },
    async onInit (promise) {
      try {
        await promise
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.error = "ERROR: Necesita proporcionar permisos para usar la camara"
        } else if (error.name === 'NotFoundError') {
          this.error = "ERROR: No existe una camara en este dispositivo"
        } else if (error.name === 'NotSupportedError') {
          this.error = "ERROR: Se requiere un contexto de seguridad (HTTPS, localhost)"
        } else if (error.name === 'NotReadableError') {
          this.error = "ERROR: La camara se encuentra en uso"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "ERROR: Las camaras no son compatibles"
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.error = "ERROR: La API no es soportada por su navegador"
        }
      }
    },
    async getControlHojaRuta(result){
      var param = {'persona_id': this.selectedItem.persona_id, 'estado': false, 'control': false, 'retenido': false}
      let res = await axios.get('api/hoja_ruta/fill/'+JSON.stringify(param))
      if(res.data.length > 0){
        var param2 = {'hoja_ruta_id': res.data[0].id}
        let res2 = await axios.get('api/control_hoja_ruta/fill/'+JSON.stringify(param2))
        for(var temp of res2.data[0].hoja_ruta.ruta.puestos_de_control){
          var hash = btoa(temp.nombre+res2.data[0].hoja_ruta.correlativo+res2.data[0].hoja_ruta.persona.carnet_identidad+res2.data[0].hoja_ruta.persona.nombre+res2.data[0].hoja_ruta.persona.primer_apellido)
          if(hash == result){
            this.control_hoja_ruta = res2.data[0]
            this.control_hoja_ruta.puesto = temp
            let res3 = await axios.get('api/control_puesto/fill/'+JSON.stringify({'control_id':this.control_hoja_ruta.id, 'puesto_id': temp.id}))
            if(res3.data.length > 0){
              this.permiso = true
            }
            else{
              this.permiso = false
            }
            return this.control_hoja_ruta
          }
        }
        return this.control_hoja_ruta = null
      }
      else{
        this.control_hoja_ruta = null
      }
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
          this.close();
        }
      }
    },
    async habilitar(){
      var param = {'persona_id': this.selectedItem.persona_id, 'estado': false, 'control': false, 'retenido': false}
      let res = await axios.get('api/hoja_ruta/fill/'+JSON.stringify(param))
      if(res.data.length > 0){
        var param2 = {'hoja_ruta_id': res.data[0].id}
        let res2 = await axios.get('api/control_hoja_ruta/fill/'+JSON.stringify(param2))
        var param3 = {'control_id': res2.data[0].id, 'user_id': this.$store.getters["auth/user"].id}
        let res3 = await axios.get('api/control_puesto/fill/'+JSON.stringify(param3))
        if(res3.data.length > 0){
          this.habilitado = false
          this.usuario = this.$store.getters["auth/user"]
        }
        else{
          this.habilitado = true
        }
      }
      else{
        return this.habilitado = true
      }
    }
  },  
};
</script>