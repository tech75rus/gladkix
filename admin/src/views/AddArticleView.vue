<template>
  <div class="article-create">
    <span v-html="text"></span>
    <div id="editor"></div>
<!--    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">-->
<!--      <path d="M272 304h-96C78.8 304 0 382.8 0 480c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32C448 382.8 369.2 304 272 304zM48.99 464C56.89 400.9 110.8 352 176 352h96c65.16 0 119.1 48.95 127 112H48.99zM224 256c70.69 0 128-57.31 128-128c0-70.69-57.31-128-128-128S96 57.31 96 128C96 198.7 153.3 256 224 256zM224 48c44.11 0 80 35.89 80 80c0 44.11-35.89 80-80 80S144 172.1 144 128C144 83.89 179.9 48 224 48z"/>-->
<!--    </svg>-->
    <div class="tags">
      <label v-for="tag in tags">
        {{ tag.name }}
        <input type="checkbox" :value=tag.id v-model="selectedTags">
      </label>
    </div>
    <button @click="loadMessage">Сохранить статью</button>
    <span class="success" v-if="success">Статья добавлена</span>
    <span class="error" v-else-if="error">Произошла ошибка при добавлении статьи</span>
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
  name: 'AddArticleView',
  data() {
    return {
      header: '',
      text: '',
      tags: '',
      selectedTags: [],
      success: '',
      error: '',
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
        if (this.selectedTags.length > 0) {
          form.append('tags', this.selectedTags);
        }
        axios.post(host + '/admin/article-create', form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(() => {
          this.success = true;
        }).catch(error => {
          this.error = true;
          console.log(error);
        })
      })
    },
    getTechnologyTags() {
      axios.get(host + '/technology-tags').then(response => {
        this.tags = response.data;
      })
    },
    getEditor() {
      new EditorJS({
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
    }
  },
   mounted() {
    this.getEditor();
    this.getTechnologyTags();
    let renderEditor = document.querySelectorAll('.codex-editor');
    if (renderEditor.length > 1) {
      renderEditor[0].remove();
    }
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
  button {
    padding: 7px;
    background-color: rgba(255, 255, 255, .2);
    border: 1px solid #d8dbdf;
    color: #d8dbdf;
    border-radius: 5px;
    font-size: 1rem;
    transition: .1s;
  }
  button:hover {
    background-color: #ff801fa1;
  }
}
.tags {
  margin-bottom: 30px;
  label {
    margin-right: 10px;
    cursor: pointer;
  }
  input {
    cursor: pointer;
  }
}
.success {
  color: #69c257;
  padding-top: 10px;
}
.error {
  color: #b24141;
  padding-top: 10px;
}
</style>