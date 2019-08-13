<template>
  <v-container fluid>
    <v-layout>
        <v-flex>
          <canvas id="overlay" style="position: absolute"/>
          <img src="/img/prueba.jpeg" id="prueba">
        </v-flex>
    </v-layout>
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
import axios from 'axios'
import * as canvas from 'canvas';
//import canvas from 'canvas'
import * as faceapi from "face-api.js";



export default {
  components: {
    
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "Nombre",
        value: "nombre",
        align: "center"
      },
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    table: [],
    search: "",
    imagen: null
  }),
  computed: {},
  mounted() {
    this.getTable();
    this.loadModels();
    this.bus.$on("closeDialog", () => {
      this.getTable();
    });
  },
  methods: {
    async getTable() {
      try {
        let res = await axios.get("api/localidad")
        this.table = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async loadModels(){
      const MODEL_URL = '/models';
      await faceapi.loadTinyFaceDetectorModel(MODEL_URL);
      await faceapi.loadFaceLandmarkTinyModel(MODEL_URL);
      await faceapi.loadFaceRecognitionModel(MODEL_URL);
      await faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
      await faceapi.nets.faceLandmark68Net.loadFromUri('/models')

      const input = document.getElementById('prueba')
      let fullFaceDescriptions = await faceapi.detectAllFaces(input).withFaceLandmarks().withFaceDescriptors()
      console.log(fullFaceDescriptions)

      const displaySize = { width: input.width, height: input.height }
      const canvas = document.getElementById('overlay')
      
      faceapi.matchDimensions(canvas, displaySize)
      faceapi.draw.drawDetections(canvas, fullFaceDescriptions)
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `api/localidad/${item.id}`);      
    }
  }
};
</script>
