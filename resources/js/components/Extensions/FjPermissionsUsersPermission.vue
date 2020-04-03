<template>
	<div>
		<span v-if="role != ''">{{ $t(`roles.${role}`) }}</span>
	</div>
</template>
<script>
export default {
	props: {
		item: {
			required: true,
			type: Object
		}
	},
	beforeMount() {
		console.log(this);
	},
	computed: {
		role() {
			return this.item.role ? this.item.role.name : '';
		}
	}
};
</script>
