<template>
  <div v-if="true">
    <form @submit.prevent="clickMessage">
      <input type="text" v-model.lazy="header">
      <textarea cols="130" rows="30" v-model="text"></textarea>
      <button>Send</button>
    </form>
    <span v-html="text"></span>
  </div>
</template>

<script>
import axios from 'axios';
import {host} from "@/service/host";

export default {
  name: 'AboutView',
  data() {
    return {
      header: '',
      text: '<p>Hello World!</p>\n' +
          '<p>Some initial <strong>bold</strong> text</p>\n' +
          '<p>Bla bla bla</p>\n',
    }
  },
  methods: {
    clickMessage() {
      let data = new FormData();
      data.append('header', this.header);
      data.append('text', this.text);
      axios.post(host + '/admin/article-create', data, {
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded'
        }
      }).then(response => {
        console.log(response);
      }).catch(error => {
        console.log(error);
      })
    },
    loadMessage() {

    }
  }
}
</script>

<style lang="scss" scoped>
form {
  display: flex;
  flex-direction: column;
}
</style>