import Vue from 'vue'
import axios from 'axios'
import { Icon } from 'leaflet'
import 'leaflet/dist/leaflet.css'

delete Icon.Default.prototype._getIconUrl;

Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
});

export default {
  props: ["item", "bus"],  
  data() {
    return {      
      valid: true,
      dialog: false,
      selectedIndex: -1,     
      selectedItem: {
        puestos_de_control:[]
      },
      municipios: [],
      markers: [],
      markersTemp: [],
      place: null,
      puestos_de_control:[],
      permanencia: [],
      error: '',
      statusText: '',
      map: null,
      layerGroup: null,
      bounds: [],
    };
  },
  created() {},  
  mounted() {
    this.initMap();
    this.bus.$on("openDialog", item => {
      this.resize();
      this.layerGroup.clearLayers();
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;
      this.zoom = 5;
      this.markersTemp = [];
      this.puestos_de_control.forEach(elem=>{
        var marker = L.marker([elem.latitud, elem.longitud],
          {MyCustomId: elem.id})
          .bindPopup(elem.nombre)
          marker.on('mouseover', function (e) {
          this.openPopup();
          });
          marker.on('mouseout', function (e) {
            this.closePopup();
          });
        var selectedMarker = item.puestos_de_control.find(mark=>{
          return mark.id == elem.id
        });
        if(selectedMarker!=undefined){
          marker.setIcon(L.icon({
            popupAnchor: [0, -36],
            iconAnchor:[11, 38],
            iconRetinaUrl: '/img/marker-icon-2x.png',
            iconUrl: '/img/marker-icon.png',
            shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
            
          }));
          this.markersTemp.push({
            id: elem.id,
            position: {
              lat: elem.latitud,
              lng: elem.longitud
            },
          });
          this.bounds.push([elem.latitud, elem.longitud]);
          L.polyline(this.bounds, {color: 'Brown'}).addTo(this.layerGroup);
          this.selectedItem.origenla = parseFloat(this.markersTemp[0].position.lat);
          this.selectedItem.origenln = parseFloat(this.markersTemp[0].position.lng);
          this.selectedItem.origen_id = this.markersTemp[0].id
          this.selectedItem.destinola = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lat);
          this.selectedItem.destinoln = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lng);
          this.selectedItem.destino_id = this.markersTemp[this.markersTemp.length - 1].id;
          this.selectedItem.puestos_de_control.push(elem.id);
          this.map.setView([elem.latitud, elem.longitud], 8);
        }
        marker.addTo(this.layerGroup);
      });
    });
    this.getEntryData();
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
    initMap(){
    this.map = L.map('map').setView([-16.290154, -63.588654], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(this.map);
    this.layerGroup = L.layerGroup();
    this.layerGroup.addTo(this.map);
    },
    resize() {
      setTimeout(()=>{
        this.map.invalidateSize();
      },50)
      this.layerGroup.clearLayers();
      this.setMarkers(this.puestos_de_control);
      this.bounds = [];
    },
    async getEntryData() {
      let res = await axios.get("api/municipio")
      this.municipios = res.data;
      let response = await axios.get("api/puesto_de_control");
      this.puestos_de_control = response.data;
      this.setMarkers(this.puestos_de_control);
    },
    setMarkers(markers) {
      for(var i = 0; i < markers.length; i++){
        var marker = L.marker([markers[i].latitud, markers[i].longitud],
          {MyCustomId: markers[i].id})
        .bindPopup(markers[i].nombre)
        .on('click',(e)=>{
          this.selectmarker(e.target);
        })
        marker.on('mouseover', function (e) {
          this.openPopup();
        });
        marker.on('mouseout', function (e) {
          this.closePopup();
        });
        marker.addTo(this.layerGroup);
      }
    }, selectmarker(marker) {
      marker.setIcon(this.selectedIcon());
      this.bounds.push(marker.getLatLng());
      L.polyline(this.bounds, {color: 'Brown'}).addTo(this.layerGroup);
      this.markersTemp.push({
        id: marker.options.MyCustomId,
        position: {
          lat: marker.getLatLng().lat,
          lng: marker.getLatLng().lng
        },
      });
      this.selectedItem.origenla = parseFloat(this.markersTemp[0].position.lat);
      this.selectedItem.origenln = parseFloat(this.markersTemp[0].position.lng);
      this.selectedItem.origen_id = this.markersTemp[0].id
      this.selectedItem.destinola = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lat);
      this.selectedItem.destinoln = parseFloat(this.markersTemp[this.markersTemp.length - 1].position.lng);
      this.selectedItem.destino_id = this.markersTemp[this.markersTemp.length - 1].id;
      this.selectedItem.puestos_de_control.push(marker.options.MyCustomId);
    },
    selectedIcon() {
      return L.icon({
        popupAnchor: [0, -36],
        iconRetinaUrl: '/img/marker-icon-2x.png',
        iconUrl: '/img/marker-icon.png',
        shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
        
      });
    },
    close() {
      this.dialog = false;
      this.$refs.form.reset()
      this.bus.$emit("closeDialog");
      this.selectedIndex = -1;
      this.selectedItem = {
        puestos_de_control:[]
      };
      this.markersTemp = [];
    },
    async save() {
      try {
        if (this.$refs.form.validate()) {
          if (this.selectedIndex != -1) {
            this.permanencia = []
            for(let temp of this.markersTemp){
              this.permanencia.push(temp.id)
            }
            this.selectedItem.puestos_de_control = this.permanencia
            console.log(this.permanencia)
            await axios.put("api/ruta/"+this.selectedItem.id, this.selectedItem)
          } else {
            await axios.post("api/ruta", this.selectedItem)
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
    codevalidate(){
      axios.get('/api/ruta/codigo/'+this.selectedItem.codigo).then(response =>{
        this.error = response.data;
      });
    }
  }
}