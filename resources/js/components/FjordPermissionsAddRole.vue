<template>
	<b-button size="sm" variant="primary" @click="visible = true">
		<fa-icon icon="plus" />
		{{ $t('fj.add_role') }}
		<b-modal v-model="visible" :title="'Rolle hinzufügen'">
			<b-form-group
				label="Enter role name"
				label-for="name"
				:state="nameState"
			>
				<b-form-input v-model="name" id="name" trim></b-form-input>
				<b-form-invalid-feedback :state="nameState">
					{{ error('name') }}
				</b-form-invalid-feedback>
			</b-form-group>
			<template v-slot:modal-footer>
				<div class="w-100">
					<b-button
						variant="primary"
						size="sm"
						class="float-right"
						@click="storeRole"
						:disabled="!nameState"
					>
						<fa-icon icon="user-tag" /> Create Role
					</b-button>
				</div>
			</template>
		</b-modal>
	</b-button>
</template>
<script>
export default {
	name: 'FjordPermissionsAddRole',
	data() {
		return {
			visible: false,
			name: '',
			erros: []
		};
	},
	methods: {
		async storeRole() {
			const { data } = await axios.post(
				'/aw-studio/fjord-permissions/role',
				{
					name: this.name
				}
			);
			window.location.reload();
		},
		error(key) {
			return 'Invalid role name.';
			/*
            if (this.errors.hasOwnProperty(key)) {
                return this.errors[key].join(', ');
			}
			*/
		}
	},
	computed: {
		nameState() {
			return name != '';
		}
	}
};
</script>
