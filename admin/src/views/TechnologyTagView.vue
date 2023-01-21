<template>
  <div class="technology-tag">
    <div class="centering">
      <h2>Список тегов</h2>
      <div class="tag" v-for="tag in tags">
        <p>
          {{ tag.name }}
        </p>
        <button @click="removeTag(tag.id)">Удалить</button>
      </div>
      <form class="add-tag" @submit.prevent="setTechnologyTag()">
        <input class="input" type="text" placeholder="Введите новый тег" v-model="tag">
        <button class="button">Добавить</button>
      </form>
      <div class="message">
        <div v-if="error" class="error">
          <span>{{ error }}</span>
        </div>
        <div v-else-if="success" class="success">
          <span>{{ success }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import {host} from "@/service/host";

export default {
  name: 'TechnologyTagView',
  data() {
    return {
      tags: [],
      tag: '',
      error: '',
      success: '',
    }
  },
  methods: {
    setTechnologyTag() {
      this.error = false;
      this.success = false;
      if (this.tag === '') {
        this.error = true;
        return;
      }
      let form = new FormData();
      form.append('name', this.tag);
      axios.post(host + '/admin/set-technology-tag', form, {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        }
      }).then(response => {
        this.success = true;
        this.getTechnologyTags();
        this.success = response.data;
      }).catch(error => {
        this.error = error.response.data;
      });
    },
    getTechnologyTags() {
      axios.get(host + '/technology-tags').then(response => {
        this.tags = response.data;
      })
    },
    removeTag(id) {
      axios.get(host + '/admin/remove-tag/' + id).then(response => {
        this.success = response.data;
        this.getTechnologyTags();
      }).catch(error => {
        this.error = error.response.data;
      })
    }
  },
  created() {
    this.getTechnologyTags();
  },
  mounted() {
  },
}
</script>

<style lang="scss" scoped>
@import "src/assets/scss/button";
@import "src/assets/scss/input";
@import "src/assets/scss/centering";

.technology-tag {
  .tag {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #292d38;
    border-radius: 7px;
    margin: 10px 0;
    padding: 7px 5%;
  }
  .add-tag {
    margin-top: 30px;
  }
  h2 {
    margin-bottom: 20px;
  }
  .message {
    margin-top: 30px;

    .success {
      color: #42b983;
    }

    .error {
      color: #B0413E;
    }
  }
}
</style>