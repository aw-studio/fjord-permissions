<template>
	<fj-base-container>
		<fj-base-header :title="$t('fj.permissions')">
			<div slot="actions-right">
				<fjord-permissions-add-role />
			</div>
		</fj-base-header>

		<b-row>
			<b-col>
				<fj-index-table
					:cols="cols"
					:tabs="roleNames"
					:items="permissions"
					:count="count"
					:loadItems="loadPermissions"
					:searchKeys="['name']"
					:nameSingular="$t('fj.permissions')"
					:namePlural="$t('fj.permissions')"
					:sortBy="config.sortBy"
					:sortByDefault="config.sortByDefault"
					:globalActions="config.globalActions"
				/>
			</b-col>
		</b-row>
	</fj-base-container>
</template>

<script>
export default {
	name: 'FjordPermissions',
	props: {
		cols: {
			type: Array,
			required: true
		},
		operations: {
			type: Array,
			required: true
		},
		roles: {
			type: Array
		},
		role_permissions: {
			type: Array
		},
		buttons: {
			type: Array
		},
		config: {
			type: Object,
			required: true
		}
	},
	data() {
		return {
			showCreateRole: false,
			count: 0,
			permissions: [],
			roleNames: [],
			update: {
				updater: null
			},
			roles_permissions: {},
			crud: ['Create', 'Read', 'Update', 'Delete']
		};
	},
	beforeMount() {
		this.$store.commit('SET_FJ_PERMISSIONS_OPERATIONS', this.operations);
		this.$store.commit(
			'SET_FJ_PERMISSIONS_ROLE_PERMISSIONS',
			this.role_permissions
		);

		for (let i = 0; i < this.roles.length; i++) {
			let role = this.roles[i];
			this.roleNames.push({
				id: role.id,
				title: this.$t(`roles.${role.name}`).toString()
			});
		}

		for (var i = 0; i < this.roles.length; i++) {
			let role = this.roles[i];
			this.roles_permissions[role.name] = {};
			for (var p = 0; p < this.permissions.length; p++) {
				let permission = this.permissions[p];
				this.roles_permissions[role.name][
					permission.name
				] = this.roleHasPermission(role, permission);
			}
		}
	},
	methods: {
		findRole(id) {
			for (let i = 0; i < this.roles.length; i++) {
				let role = this.roles[i];
				if (role.id == id) {
					return role;
				}
			}
		},
		async loadPermissions(payload) {
			this.$store.commit(
				'SET_FJ_PERMISSIONS_ROLE',
				this.findRole(payload.tab.id)
			);
			let response = await axios.post(
				'aw-studio/fjord-permissions/index',
				payload
			);
			this.permissions = response.data.unique_items;
			this.count = response.data.count;

			this.$store.commit(
				'SET_FJ_PERMISSIONS_PERMISSIONS',
				response.data.items
			);
		}
	}
};
</script>
