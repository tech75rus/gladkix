<template>
  <div class="home">
    <h1>{{ article.header }}</h1>
    <div v-html="article.article" class="block"></div>
  </div>
</template>

<script>
import hljs from "highlight.js";
import axios from "axios";
import {host} from "@/service/host";

export default {
  name: 'ArticleView',
  data() {
    return {
      article: ''
    }
  },
  mounted() {
    hljs.highlightAll();
    axios.get( host +'/article/' + this.$route.params.id).then(response => {
      this.article = response.data;
    })
  }
}
</script>

<style lang="scss">
@import "highlight.js/scss/monokai";
.block {
  text-align: left;
}
</style>