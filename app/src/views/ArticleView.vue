<template>
  <div class="home">
    <h1>{{ article.header }}</h1>
    <div v-html="article.article" class="block"></div>
  </div>
</template>

<script>
import hljs from "highlight.js";
import axios from "axios";

export default {
  name: 'ArticleView',
  data() {
    return {
      article: ''
    }
  },
  mounted() {
    hljs.highlightAll();
    axios.get('https://localhost/article/' + this.$route.params.id).then(response => {
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