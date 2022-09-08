<template>
  <div v-if="true">
    <form @submit.prevent="clickMessage">
      <input type="text" v-model.lazy="header">
      <textarea cols="130" rows="30" v-model="text"></textarea>
      <button>Send</button>
    </form>
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
      text: '<p>Hello World!</p>\n' +
          '<p>Some initial <strong>bold</strong> text</p>\n' +
          '<p>Bla bla bla</p>\n',
      editor: '',
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
        image: Image,
        // image: {
        //   class: Image,
        //   config: {
        //     endpoints: {
        //       byFile: 'https://localhost:8000/uploadFile', // Your backend file uploader endpoint
        //       byUrl: 'https://localhost:8000/fetchUrl', // Your endpoint that provides uploading by Url
        //     }
        //   }
        // },
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