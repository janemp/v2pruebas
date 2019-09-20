<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout wrap>
            <v-flex xs12 sm4 md4>
              <v-card-text>
                <v-select
                  :items="municipios"
                  item-text="nombre"
                  item-value="id"
                  v-model="selectedItem.municipio_id"
                  label="Municipio"
                  :rules="[v => !!v || 'Requerido']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-select>
                <v-text-field
                  v-model="selectedItem.codigo"
                  label="Codigo"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 5) || 'No mayor a 5 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  :error-messages="error"
                  @change="codevalidate()"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.nombre"
                  label="Nombre"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 100) || 'No mayor a 100 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-textarea
                  v-model="selectedItem.descripcion"
                  label="Descripción"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-textarea>
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm4 md4>
              <v-card-text>                
                <v-text-field
                  v-model="selectedItem.latitud"
                  label="Latitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.longitud"
                  label="Longitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-text-field>
                <v-textarea
                  v-model="selectedItem.direccion"
                  label="Dirección"
                  :rules="[v => !!v || 'Requerido', v => (v && v.length <= 500) || 'No mayor a 500 caracteres']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                ></v-textarea>               
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm4 md4>
              <v-card-text>                
                    <gmap-autocomplete
                      placeholder="Buscar Zonas,Calles,Mercados,etc...."
                      @place_changed="setPlace"
                      style="width: 100%;">
                    </gmap-autocomplete>
                  <br/>

                  <Gmap-Map style="width: 260px; height: 350px;" :zoom="zoom" :center="{lat: lat, lng: lng}" @click="mapRclicked">
                    <Gmap-Marker v-for="(marker, index) in markers"
                      :key="index"
                      :position="marker.position"
                      ></Gmap-Marker>
                    <Gmap-Marker
                      v-if="this.place"
                      label=""
                      :position="{
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng(),
                      }"
                      ></Gmap-Marker>
                  </Gmap-Map> 
              </v-card-text>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" small @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" small :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script src="./Form_puesto.js">
</script>