<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary" v-on:click.capture="resize">add_circle</v-icon>
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
                  v-model="selectedItem.origenla"
                  label="Origen:  Latitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.origenln"
                  label="Origen: Longitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.destinola"
                  label="Destino: Latitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.destinoln"
                  label="Destino: Longitud"
                  type="number"
                  :rules="[v => !!v || 'Requerido', v => /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/.test(v) || 'latitud incorrecta']"
                  hint="<span class='blue--text'>*Requerido</span>" persistent-hint
                  disabled
                ></v-text-field>              
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm4 md4>
              <v-card-text>                
                  <span>Elija los puntos de control de la ruta de forma ordenada desde el origen hasta el destino</span>
                  <div id="map" style="height: 300px" class="map"></div>
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

<script src="./form.js" type="text/javascript">
</script>