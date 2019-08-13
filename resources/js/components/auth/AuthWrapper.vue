<template>
  
  <v-content style="background-image: url('img/coca1.png');background-size: 100% 100%;">
    <v-container fluid fill-height justify-center align-center>
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </v-container>
  </v-content>
  
</template>

<script>
  export default {
  }
</script>
