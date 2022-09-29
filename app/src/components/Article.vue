<template>
  <div class="article">
    <div class="block" v-for="block in article">
      <h2 v-if="block.type === 'header'">
        {{ block.data.text }}
      </h2>
      <p v-if="block.type === 'paragraph'" v-html="block.data.text"></p>
      <pre v-if="block.type === 'raw'">
        <code class="plaintext">
        {{ block.data.html }}
        </code>
      </pre>
      <div v-if="block.type === 'image'">
        <img :src="block.data.file.url" alt="">
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import {host} from "@/service/host";
import hljs from "highlight.js";

export default {
  name: 'Article',
  props: {
    articleId: String
  },
  data() {
    return {
      article: ''
    }
  },
  async mounted() {
    await axios.get(host + '/article/' + this.$route.params.id).then(response => {
      this.article = JSON.parse(response.data.article).blocks;
    });
    hljs.highlightAll();
  },
}
</script>

<style lang="scss" scoped>
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