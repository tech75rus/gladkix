<template>
	<div class="empty">
		<div class="main">
			<router-view/>
		</div>
	</div>
</template>
<script>
import Registration from "../views/Registration";
import Login from "../views/Login";

export default {
	name: 'EmptyLayout',
	components: {
		Registration, Login
	},
}
</script>
<style scoped lang="scss">
@import "src/assets/scss/color";

.empty{
	width: 100%;
	min-height: 80vh;
	display: flex;
	justify-content: center;
	align-items: center;
}
.main {
	display: flex;
	justify-content: center;
	max-width: 400px;
  width: 90%;
	min-height: 400px;
	background-color: $main-color;
	border-radius: 5px;
	padding-top: 20px;
	text-align: center;
}
</style>
