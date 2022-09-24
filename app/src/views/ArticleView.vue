<template>
  <div class="home">
    <div id="article"></div>
  </div>
</template>

<script>
import hljs from "highlight.js";
import axios from "axios";
import {host} from "@/service/host";
import {Article} from "@/service/Article";


export default {
  name: 'ArticleView',
  data() {
    return {
      article: '',
      editor: '',
    }
  },
  async mounted() {
    await axios.get( host +'/article/' + this.$route.params.id).then(response => {
      this.article = response.data.article;
    });

    new Article(this.article, '#article');
    hljs.highlightAll();
  }
}
</script>

<style lang="scss">
@import "highlight.js/scss/monokai";
.home {
  font-size: 1.15rem;
  h1 {
    text-align: center;
  }
  h2 {
    text-align: center;
    margin-bottom: 10px;
  }
  p {
    text-align: left;
    margin-bottom: 6px;
  }
  pre {
    text-align: left;
    margin: 10px 0;
  }
  img {
    text-align: center;
    margin: 10px 0;
    max-width: 100%;
  }
}
</style>