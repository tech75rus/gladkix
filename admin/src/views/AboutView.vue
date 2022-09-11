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

export default {
  name: 'AboutView',
  data() {
    return {
      header: '',
      editor: '',
    }
  },
  methods: {
    loadMessage() {
      this.editor.save().then(result => {
        console.log(result);
      })
    }
  },
  mounted() {
    this.editor = new EditorJS({
      holder: 'editor',
      autofocus: true,
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
      },
      data: {
        "time": 1662637183472,
        "blocks": [
          {
            "id": "QlG9OEvduX",
            "type": "header",
            "data": {
              "text": "Hello",
              "level": 1
            }
          },
          {
            "id": "oN2o2APr1C",
            "type": "paragraph",
            "data": {
              "text": "This is description"
            }
          }
        ],
        "version": "2.25.0"
      }
    });
  },
}
</script>

<style lang="scss" scoped>
form {
  display: flex;
  flex-direction: column;
}
</style>