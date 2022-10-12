<template>
  <div id="window">
    <component :is="layout">
      <router-view />
    </component>
    <div v-if="$store.getters.getLoader" class="spin-wrapper">
      <div class="spinner"></div>
    </div>
  </div>
</template>

<script>
import {defineAsyncComponent} from "vue";
const MainLayout = defineAsyncComponent(() =>
    import('./layouts/MainLayout')
);
const EmptyLayout = defineAsyncComponent(() => {
  import('./layouts/EmptyLayout')
})
export default {
  name: 'App',
  components: {
    MainLayout,
    EmptyLayout
  },
  computed: {
    layout() {
      return (this.$route.meta.layout || 'empty') + '-layout';
    }
  },
  methods:{
  }
}
</script>

<style lang="scss">
@import "assets/scss/color";

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  -webkit-tap-highlight-color: transparent; //при клике на кнопки и изображения не мигает синим
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-decoration: none;
}
h1, h2, h3, h4, h5, h6, p, label, input, div {
  color: $color-text;
}
body {
  background-color: $background-color;
}
button {
  cursor: pointer;
}
#window {
  background-color: $background-color;
  min-height: 100vh;
  width: 100%;
  position: relative;
}
// анимация загрузки
.spin-wrapper{
  position: fixed;
  top: 0;
  width: 100%;
  height: 100vh;
  background: $background-color;
  z-index: 1000;

  .spinner{
    position: absolute;
    height: 60px;
    width: 60px;
    border: 3px solid transparent;
    border-top-color: #A04668;
    top: 50%;
    left: 50%;
    margin: -30px;
    border-radius: 50%;
    animation: spin 2s linear infinite;

    &:before, &:after{
      content:'';
      position: absolute;
      border: 3px solid transparent;
      border-radius: 50%;
    }

    &:before{
      border-top-color: #254E70;
      top: -12px;
      left: -12px;
      right: -12px;
      bottom: -12px;
      animation: spin 3s linear infinite;
    }

    &:after{
      border-top-color: #FFFBFE;
      top: 6px;
      left: 6px;
      right: 6px;
      bottom: 6px;
      animation: spin 4s linear infinite;
    }
  }
}
@keyframes spin{
  0% {transform: rotate(0deg);}
  100% {transform: rotate(360deg);}
}
</style>
