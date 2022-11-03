<template>
  <div v-if="true" class="article-create">
    <span v-html="text"></span>
    <div id="editor"></div>
<!--    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">-->
<!--      <path d="M272 304h-96C78.8 304 0 382.8 0 480c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32C448 382.8 369.2 304 272 304zM48.99 464C56.89 400.9 110.8 352 176 352h96c65.16 0 119.1 48.95 127 112H48.99zM224 256c70.69 0 128-57.31 128-128c0-70.69-57.31-128-128-128S96 57.31 96 128C96 198.7 153.3 256 224 256zM224 48c44.11 0 80 35.89 80 80c0 44.11-35.89 80-80 80S144 172.1 144 128C144 83.89 179.9 48 224 48z"/>-->
<!--    </svg>-->
    <button @click="loadMessage">Сохранить статью</button>
  </div>
</template>

<script>
import axios from 'axios';
import {host} from "@/service/host";
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Image from '@editorjs/image';
import Raw from '@editorjs/raw';

export default {
  name: 'AboutView',
  data() {
    return {
      header: '',
      editor: '',
      text: '',
    }
  },
  methods: {
    loadMessage() {
      this.editor.save().then(result => {
        let form = new FormData();
        form.append('article', JSON.stringify(result));
        let onceHeader = true;
        let onceShortArticle = true;
        for (let block of result.blocks) {
          if (onceHeader && block.type === 'header') {
            form.append('header', block.data.text);
            onceHeader = false;
          }
          if (onceShortArticle && block.type === 'paragraph') {
            form.append('short_article', block.data.text);
            onceShortArticle = false;
          }
        }
        axios.post(host + '/admin/article-create', form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          console.log(response);
        })
      })

    }
  },
  mounted() {
    this.editor = new EditorJS({
      holder: 'editor',
      autofocus: true,
//      readOnly: true,
      tools: {
        header: {
          class: Header,
          inlineToolbar : false
        },
        image: {
          class: Image,
          config: {
            endpoints: {
              byFile: host + '/image-file', // Your backend file uploader endpoint
            }
          }
        },
        raw: {
          class: Raw
        }
      },
    });
  },
}
</script>

<style lang="scss">
.ce-block__content {
  background: #292d38;
  border-radius: 5px;
  margin: 5px auto;
}
.article-create {
  svg {
    width: 30px;
    height: 30px;
    fill: #7569d1;
    transition: .3s;
  }
  .svg {
    width: 150px;
    height: 150px;
    fill: #7569d1;
    transition: .3s;
  }
  .svg:hover {
    fill: black;
    cursor: pointer;
    filter: drop-shadow(0 0 1px #7569d1) drop-shadow(0 0 1px #7569d1) drop-shadow(0 0 25px #7569d1);
  }

  svg:hover {
    fill: black;
    cursor: pointer;
    filter: drop-shadow(0 0 1px #7569d1) drop-shadow(0 0 1px #7569d1) drop-shadow(0 0 25px #7569d1);
  }
}
</style>