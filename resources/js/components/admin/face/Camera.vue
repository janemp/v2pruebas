<template>
    <v-content>
      <v-layout>
          <span v-if="reconocido == false">No se reconocio el rostro</span>
          <span v-if="reconocido == true">Rostro reconocido exitosamente</span>
        </v-layout>
        <v-layout>
            <v-flex>
                <WebCam
                    ref="webcam"
                    width="auto"
                    height="100%"
                    >
                </WebCam>
              <v-btn
                xs3
                color="primary"
                @click="onCapture()"
              >Ejecutar reconocimiento facial <v-icon>camera</v-icon>
              </v-btn>
              <v-avatar
                style="display: none"
                tile="tile"
                size="auto"
                color="grey lighten-4"
              ><img :src="img" ref="prueba"/>
              </v-avatar>
            </v-flex>
        </v-layout>
        
    </v-content>
</template>
<script>
import Vue from 'vue'
import axios from 'axios'
import * as canvas from 'canvas'
import * as faceapi from 'face-api.js'
import { WebCam } from 'vue-cam-vision'

export default {
    components: {
        WebCam
    },
    data(){
        return {
          captures: [],
          imgReport: [],
          frontCam: false,
          webcam: null,
          img: null,
          camera: null,
          deviceId: null,
          devices: [],
          reconocido: false
        }
    },
    mounted(){
      this.loadModels()
    },
    methods: {
      async loadModels(){
        const MODEL_URL = '/models';
        await faceapi.loadTinyFaceDetectorModel(MODEL_URL);
        await faceapi.loadFaceLandmarkTinyModel(MODEL_URL);
        await faceapi.loadFaceRecognitionModel(MODEL_URL);
        await faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
        await faceapi.nets.faceLandmark68Net.loadFromUri('/models')
      },
      async onCapture () {
        this.reconocido = false
        this.img = await this.$refs.webcam.capture()
        // Deteccion de rostros
        const input = this.$refs.prueba
        let fullFaceDescriptions = await faceapi.detectAllFaces(input).withFaceLandmarks().withFaceDescriptors()
        const displaySize = { width: input.width, height: input.height }
        // Reconocimiento de rostros
        const labels = ['img/felix']

        const labeledFaceDescriptors = await Promise.all(
            labels.map(async label => {
                const imgUrl = `${label}.jpg`
                const img = await faceapi.fetchImage(imgUrl)
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
            if(text.indexOf(labels[0]) > -1){
              this.reconocido = true
            }
        })
      },
      onStarted (stream) {
        console.log('On Started Event', stream)
      },
      onStopped (stream) {
        console.log('On Stopped Event', stream)
      },
      onStop () {
        this.$refs.webcam.stop()
      },
      onStart () {
        this.$refs.webcam.start()
      },
      onError (error) {
        console.log('On Error Event', error)
      },
      onCameras (cameras) {
        this.devices = cameras
        console.log('On Cameras Event', cameras)
      },
      onCameraChange (deviceId) {
        this.deviceId = deviceId
        this.camera = deviceId
        console.log('On Camera Change Event', deviceId)
      }    
    },
    
}
</script>