<template>
  <v-dialog persistent v-model="dialog" max-width="28%" @keydown.esc="close">
    <v-card>
      <v-card-text>
        <div class="headline font-weight-regular">¿Está seguro de realizar la accion "{{ accion }}" en este registro?</div>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="warning" small @click="close"><v-icon small>check</v-icon> Cancelar</v-btn>
        <v-btn color="success" small @click="save"><v-icon small>close</v-icon> Si</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from 'axios'

export default {
  name: "RemoveItem",
  props: ["url", "bus"],
  data() {
    return {
      path: '',
      dialog: false,
      accion: null,
      seletedItem: {},
    };
  },
  methods: {
    resetVariables() {
      this.path = '';
    },
    close() {
      this.resetVariables();
      this.dialog = false;
      this.bus.$emit("closeDialog");
    },
    async save() {
      try {
        let res = await axios.put(this.path, this.seletedItem);
        console.log(res.data)
        this.$toast.success('Correcto.')
        this.close()
      } catch (e) {
        console.log(e);
      }
    }
  },
  mounted() {
    this.bus.$on("openDialogConfirm", data => {
      this.path = data.url;
      this.seletedItem = data.item
      this.accion = data.action
      this.dialog = true;
      console.log(data)
    });
  }
};
</script>