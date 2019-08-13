<template>
  <v-container fluid>
    <v-layout>
        <v-flex>
          <canvas ref="overlay" style="position: absolute"/>
          <!-- <video id="prueba" autoplay muted></video> -->
          <img src="/img/family.jpg" ref="prueba">
        </v-flex>
    </v-layout>
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
import axios from 'axios'
import * as canvas from 'canvas';
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
  computed: {

  },
  mounted() {
    //this.entrada()
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
    async entrada(){
        const stream = await navigator.mediaDevices.getUserMedia({ video: {} })
        const videoEl = document.getElementById('prueba')
        videoEl.srcObject = stream

        const displaySize = { width: videoEl.width, height: videoEl.height }
        const canvas = document.getElementById('overlay')
        
        faceapi.matchDimensions(canvas, displaySize)
        this.onPlay()
    },
    async loadModels(){
        const MODEL_URL = '/models';
        await faceapi.loadTinyFaceDetectorModel(MODEL_URL);
        await faceapi.loadFaceLandmarkTinyModel(MODEL_URL);
        await faceapi.loadFaceRecognitionModel(MODEL_URL);
        await faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
        await faceapi.nets.faceLandmark68Net.loadFromUri('/models')
        // Deteccion de rostros
        const input = this.$refs.prueba
        let fullFaceDescriptions = await faceapi.detectAllFaces(input).withFaceLandmarks().withFaceDescriptors()
        console.log(fullFaceDescriptions)

        const displaySize = { width: input.width, height: input.height }
        const canvas = this.$refs.overlay
        
        faceapi.matchDimensions(canvas, displaySize)
        faceapi.draw.drawDetections(canvas, fullFaceDescriptions)
        // Reconocimiento de rostros
        const labels = ['img/felix']

        const labeledFaceDescriptors = await Promise.all(
            labels.map(async label => {
                // fetch image data from urls and convert blob to HTMLImage element
                const imgUrl = `${label}.jpg`
                const img = await faceapi.fetchImage(imgUrl)
                
                // detect the face with the highest score in the image and compute it's landmarks and face descriptor
                const fullFaceDescription = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
                
                if (!fullFaceDescription) {
                throw new Error(`no faces detected for ${label}`)
                }
                
                const faceDescriptors = [fullFaceDescription.descriptor]
                return new faceapi.LabeledFaceDescriptors(label, faceDescriptors)
            })
        )

        const maxDescriptorDistance = 0.6
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, maxDescriptorDistance)

        const results = fullFaceDescriptions.map(fd => faceMatcher.findBestMatch(fd.descriptor))

        results.forEach((bestMatch, i) => {
            const box = fullFaceDescriptions[i].detection.box
            const text = bestMatch.toString()
            const drawBox = new faceapi.draw.DrawBox(box, { label: text })
            drawBox.draw(canvas)
        })
    },
    async onPlay() {
        console.log('ok')
      const videoEl = document.getElementById('prueba')

      const result = await faceapi.detectSingleFace(videoEl).withFaceLandmarks().withFaceDescriptor()


      if (result) {
        const canvas = document.getElementById('overlay')
        const dims = faceapi.matchDimensions(canvas, videoEl, true)
        faceapi.draw.drawDetections(canvas, faceapi.resizeResults(result, dims))
      }
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
