<template>
  <div v-if="true">
    <span v-html="text"></span>
    <div id="editor"></div>
    <button @click="loadMessage">Save</button>

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
</style>