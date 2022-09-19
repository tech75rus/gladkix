<template>
  <div class="home">
    <div id="editor">
    </div>
    <!--    <h1>{{ article.header }}</h1>-->
<!--    <div v-html="article.article" class="block"></div>-->
  </div>
</template>

<script>
import hljs from "highlight.js";
import axios from "axios";
import {host} from "@/service/host";
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Image from '@editorjs/image';
import Raw from '@editorjs/raw';


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

    const editor = new EditorJS({
      holder: 'editor',
      readOnly: true,
      tools: {
        header: {
          class: Header,
          inlineToolbar : false
        },
        raw: {
          class: Raw
        },
        image: {
          class: Image,
          config: {
            endpoints: {
              byFile: host + '/article/image-file', // Your backend file uploader endpoint

            }
          }
        },
      },
      data: JSON.parse(this.article),
    })

    hljs.highlightAll();
  }
}
</script>

<style lang="scss">
@import "highlight.js/scss/monokai";
.block {
  text-align: left;
}
</style>