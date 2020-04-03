<template>
	<div class="d-inlune-block">
		<b-dropdown right variant="primary" size="sm">
			<template v-slot:button-content>
				<fa-icon icon="cogs" />
			</template>
			<b-dropdown-group :header="$t('fj.assign_role')">
				<b-dropdown-item
					href="#"
					v-for="(role, key) in roles"
					:key="key"
					@click="assignRole(role)"
					>{{ $t(`roles.${role.name}`) }}</b-dropdown-item
				>
			</b-dropdown-group>
		</b-dropdown>
	</div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
	name: 'FjPermissionsApplyToUser',
	props: {
		item: {
			type: Object,
			required: true
		}
	},
	data() {
		return {
			visible: false,
			message: {
				subject: null,
				text: null
			}
		};
	},
	methods: {
		async assignRole(role) {
			let response = await axios.post(
				`aw-studio/fjord-permissions/user/${this.item.id}/role`,
				{
					role_id: role.id
				}
			);

			this.$bvToast.toast(
				this.$t('fj.role_assigned', {
					username: this.item.name,
					role: this.$t(`roles.${role.name}`)
				}),
				{
					variant: 'success'
				}
			);

			this.$emit('reload');
		}
	},
	computed: {
		...mapGetters(['roles'])
	}
};
</script>
