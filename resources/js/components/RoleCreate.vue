<template>
    <b-button variant="primary" @click="visible = true">
        <fa-icon icon="plus" />
        {{ $t('fj.add_model', { model: $t('fj.role') }) }}
        <b-modal
            v-model="visible"
            :title="$t('fj.add_model', { model: $t('fj.role') })"
        >
            <b-form-group
                :label="$t('fj.enter_rolename')"
                label-for="name"
                :state="nameState"
            >
                <b-form-input v-model="name" id="name" trim />
                <b-form-invalid-feedback :state="nameErrorState">
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
                        <fa-icon icon="user-tag" />
                        {{ $t('fj.create_model', { model: $t('fj.role') }) }}
                        <b-spinner
                            label="Loading..."
                            small
                            v-if="busy"
                        ></b-spinner>
                    </b-button>
                </div>
            </template>
        </b-modal>
    </b-button>
</template>
<script>
export default {
    name: 'RoleCreate',
    data() {
        return {
            visible: false,
            name: '',
            busy: false,
            errors: []
        };
    },
    methods: {
        async storeRole() {
            this.busy = true;
            let response = null;
            try {
                response = await axios.post('/role', {
                    name: this.name
                });
            } catch (e) {
                this.errors = e.response.data.errors;
                this.busy = false;
                return;
            }

            let role = response.data;
            this.$emit('created', role);
            this.visible = false;

            this.$bvToast.toast(
                this.$t('fjpermissions.added_role', {
                    role: role.name.capitalize()
                }),
                {
                    variant: 'success'
                }
            );
            this.busy = false;
        },
        error(key) {
            if (this.errors.hasOwnProperty(key)) {
                return this.errors[key].join(', ');
            }
        }
    },
    computed: {
        nameState() {
            return this.name.length > 1;
        },
        nameErrorState() {
            return !this.errors.hasOwnProperty('name');
        }
    }
};
</script>
