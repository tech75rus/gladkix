<template>
	<div>
		<h2>Регистрация</h2>
		<form @submit.prevent="getRegistration">
			<input type="text" placeholder="Введите имя" v-model.lazy="name">
			<input type="email" placeholder="Введите email" v-model.lazy="email">
			<input type="password" placeholder="Введите пароль" v-model.lazy="password">
			<input type="password" placeholder="Введите еще раз пароль" v-model.lazy="checkPassword" @change="getChangePassword()">
			<router-link to="/login">Вход</router-link>
			<button>Регистрация</button>
		</form>
		<div v-if="success" style="color: #42b983">
			{{ success }}
		</div>
		<div v-else-if="error" style="color: #dc2363">
			{{ error }}
		</div>
	</div>
</template>

<script>
import axios from "axios";
import {host} from "@/service/host";
export default {
	name: "Registration",
	data() {
		return {
			name: '',
			email: '',
			password: '',
			checkPassword: '',
			success: '',
			error: ''
		}
	},
	mounted() {
		this.$store.dispatch('actionLoader');
	},
	methods: {
		getRegistration() {
			let form = new FormData();
			form.append('name', this.name);
			form.append('email', this.email);
			form.append('password', this.password);

			axios.post(host + '/registration',
				form, {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					}
			})
			.then(success => {
				this.success = success.data;
				this.error = '';
			}).catch(error => {
				this.error = error.response.data;
				this.success = '';
			})
		},
		getChangePassword() {
			return this.password === this.checkPassword;
		}
	}
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/input";
@import "src/assets/scss/button";
div {
	width: 100%;
	form {
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	h2 {
		margin-bottom: 20px;
	}
	a {
		margin-top: 15px;
		margin-bottom: 15px;
		color: black;
		font-size: 14px;
		&:hover {
			color: #860dd2;
		}
	}
}
</style>
