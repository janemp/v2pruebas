<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="indigo">add_circle</v-icon>
      <span>Nuevo</span>
    </v-tooltip>
    <Donar :bus="bus" />
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form1" v-model="valid" lazy-validation>
          <v-autocomplete                      
            v-model="selectedItem.persona_id"
            :items="beneficiarios"
            item-text="carnet_identidad"
            item-value="id"
            label="Carnet de identidad"
            :rules="[v => !!v || 'Requerido']"
            hint="<span class='blue--text'>*Requerido</span>" persistent-hint
            @change="getBeneficiario()"
            >
          </v-autocomplete>
        </v-form>
        <v-card flat color="blue-grey lighten-5" v-if="beneficiario">
          <v-card-text >
            <v-flex>
              <v-data-table
                :headers="headers"
                :items="beneficiario.resoluciones_administrativas"
                :search="search"
                :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
                disable-initial-sort
                class="elevation-1">
                <template slot="items" slot-scope="props">
                  <tr>
                    <td class="text-xs-left"> {{ props.item.resolucion }} </td>
                    <td class="text-xs-center"> {{ props.item.tipo_resolucion.nombre }} </td>
                    <td class="text-xs-center"> {{ props.item.fecha_resolucion }} </td>
                    <td class="text-xs-center"> {{ props.item.numero_taques }} </td>
                    <td>
                      <v-tooltip top v-if="props.item.tipo_resolucion_id == 6">
                        <v-btn slot="activator" flat icon color="indigo" @click="donarItem(props.item)">
                          <v-icon>check_circle</v-icon>
                        </v-btn>
                        <span>Registrar donación</span>
                      </v-tooltip>
                      <v-tooltip top v-else>
                        <v-btn slot="activator" flat icon color="indigo" @click="donarItem(props.item)" disabled>
                          <v-icon>check_circle</v-icon>
                        </v-btn>
                        <span>Registrar donación</span>
                      </v-tooltip>
                    </td>
                  </tr>
                </template>  
                <v-alert slot="no-results" :value="true" color="error" icon="fa fa-times">
                  Tu Busqueda de "{{ search }}" no encontró resultados.
                </v-alert>
                <template slot="no-data">
                  <v-alert slot="no-results" :value="true" color="info" icon="fa fa-times">
                    No se encontro ninguna resolución administrativa
                  </v-alert>
                </template>
            </v-data-table>
            </v-flex>
          </v-card-text>
        </v-card>                             
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
import DatetimePicker from 'vuetify-datetime-picker'
import Donar from './Donar'

Vue.use(DatetimePicker)

export default {
  components: {
    Donar
  },  
  props: ["item", "bus"], 
  data() {
    return {
      step: 1,
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      beneficiarios: [],
      headers: [
        {
          text: "Resolución",
          value: "resolucion",
          align: "center"
        },
        {
          text: "Tipo de resolucion",
          value: "tipo_resolucion.nombre",
          align: "center"
        },
        {
          text: "Fecha resolución",
          value: "fecha_resolucion",
          align: "center"
        },
        {
          text: "Numero de taques",
          value: "numero_taques",
          align: "center",
          sortable: false
        },
        {
          text: "Opciones",
          align: "center",
          sortable: false
        }
      ],
      temp: [],
      beneficiario: null,
      search: "",
    };
  },
  created() {
    
  }, 
  mounted() {
    this.bus.$on("openDialog", item => {
      this.dialog = true      
      this.selectedIndex = item
      this.selectedItem = item
      this.begin_date_formatted = this.$moment(item.fecha_inicio).format("DD/MM/YYYY")
      this.end_date_formatted = this.$moment(item.fecha_final).format("DD/MM/YYYY")
    });
    this.bus.$on('closeDialogIniciar', () => {
      this.close();
    });
    this.bus.$on("closeDialogDonar", () => {
      this.close()
    })
    this.getBeneficiarios()
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
      this.comercializador = null
      this.verifica = false;
      this.encontrado = {};
      this.dialog = false;
      this.selectedIndex = -1
      this.selectedItem = {}
      this.beneficiario = null      
      this.bus.$emit("closeDialog")
    },
    async save() {
      try {
        this.selectedItem.user_id = this.$store.getters["auth/user"].id
        if (this.$refs.form3.validate()) {          
          if (this.selectedIndex != -1) {
            await axios.put("api/hoja_ruta/"+this.selectedItem.id, this.selectedItem)
          } else {
            this.selectedItem.id_hoja_ruta = this.correlativo
            await axios.post("api/hoja_ruta", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    async getBeneficiarios() {
      var param = {'tipo_persona_id': 6}
      let res = await axios.get("api/persona/fill/"+JSON.stringify(param))
      this.beneficiarios = res.data
      
    },
    async getBeneficiario() {
      let res = await axios.get("api/persona/fill/"+JSON.stringify({'id': this.selectedItem.persona_id}))
      this.beneficiario = res.data[0]
      var temp = this.beneficiario.resoluciones_administrativas
      for(var i = 0; i < temp.length; i++){
        if(temp[i].activo == false){
          temp.splice(i, 1)
        }
      }
      this.beneficiario.resoluciones_administrativas = temp
      console.log(this.beneficiario.resoluciones_administrativas)
    },
    fullName(item) {
      return [item.nombre, item.primer_apellido, item.segundo_apellido].join(" ")
    },
    donarItem(item) {
      item.resolucion_administrativa_id = item.id
      item.beneficiario_donacion_id = item.id
      console.log(item)
      this.bus.$emit("openDialogDonar", item);
    },
  },  
};
</script>