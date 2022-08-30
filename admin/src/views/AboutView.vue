<template>
  <div v-if="true">
    <p>Hello World!</p>
    <p>Some initial <strong>bold</strong> text</p>
    <p><br></p>
    <textarea cols="130" rows="30" v-model="message"></textarea>
    <br>
    <button @click="clickMessage">Click</button>
    <br>
    <span v-html="message"></span>
  </div>
</template>

<script>
//import 'quill/quill';
//import Quill from "quill/quill";


export default {
  name: 'AboutView',
  data() {
    return {
      message: '<p>Hello World!</p>\n' +
          '<p>Some initial <strong>bold</strong> text</p>\n' +
          '<p>Bla bla bla</p>\n'
    }
  },
  methods: {
    clickMessage() {
      console.log(this.message);
    },
    loadMessage() {

    }
  }
}
</script>

<style lang="scss">
</style>