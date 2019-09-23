import Vue from 'vue'
import axios from 'axios'
import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster'

Vue.component('GmapCluster', GmapCluster)
Vue.use(VueGoogleMaps, {
 load: {
   key: 'AIzaSyBThvlm_swZHMIHmwqYOidrWr8pQStvSsM',
   libraries: 'places',
   language: 'es'
 },
})

export default {
  props: ["item", "bus"],  
  data() {
    return {      
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {},
      municipios: [],
      markers: [],
      place: null,
      lat: -16.290154,
      lng: -63.588654,
      zoom: 5,
      error: ''
    };
  },
  created() {},  
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;
      this.lat = parseFloat(item.latitud);
      this.lng = parseFloat(item.longitud);
      this.zoom = 15;
      this.markers = [];
      this.markers.push({
        id: this.lastId,
        position: {
          lat: parseFloat(item.latitud),
          lng: parseFloat(item.longitud)
        },
        opacity: 1,
        draggable: true,
        enabled: true,
        clicked: 0,
        rightClicked: 0,
        dragended: 0,
        ifw: true,
        ifw2text: item.nombre,
      });
    });
    this.getMunicipios()
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
    async getMunicipios() {
      let res = await axios.get("api/municipio")
      this.municipios = res.data
    },
    close() {
      this.markers = [];
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialog");
      this.selectedIndex = -1;
      this.selectedItem = {}
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          if (this.selectedIndex != -1) {
            await axios.put("api/puesto_de_control/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/puesto_de_control", this.selectedItem)
          }
          this.$toast.success('Correcto.')
          this.close();
        }
      } catch(e) {
        console.log(e)
      }
    },
    setDescription(description) {
      this.description = description;
    },
    setPlace(place) {
      this.markers = [];
      //this.place = place
      this.selectedItem.latitud = place.geometry.location.lat();
      this.selectedItem.longitud = place.geometry.location.lng();
      this.lat = place.geometry.location.lat();
      this.lng = place.geometry.location.lng();
      this.zoom = 12;
    },
    usePlace(place) {
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
        })
        this.place = null;
      }
    }, 
    mapRclicked(mouseArgs) {
      const createdMarker = this.addMarker();
      createdMarker.position.lat = mouseArgs.latLng.lat();
      createdMarker.position.lng = mouseArgs.latLng.lng();
      this.selectedItem.latitud = mouseArgs.latLng.lat();
      this.selectedItem.longitud = mouseArgs.latLng.lng();
    },
    addMarker: function addMarker() {
      this.lastId++;
      this.markers = [];
      this.markers.push({
        id: this.lastId,
        position: {
          lat: 48.8538302,
          lng: 2.2982161
        },
        opacity: 1,
        draggable: true,
        enabled: true,
        clicked: 0,
        rightClicked: 0,
        dragended: 0,
        ifw: true,
        ifw2text: 'This text is bad please change me :( '
      });
      return this.markers[this.markers.length - 1];
    },
    codevalidate(){
      axios.get('/api/puesto_de_control/codigo/'+this.selectedItem.codigo).then(response =>{
        this.error = response.data;
      });
    }
  }
};