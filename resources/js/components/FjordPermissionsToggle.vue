<template>
    <div>
        <b-form-checkbox
            v-model="checked"
            name="check-button"
            @change="change"
            :disabled="!permission"
            switch/>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    name: 'FjordPermissionsToggle',
    props: {
        item: {
            required: true,
            type: [Object, Array]
        },
        col: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            checked: false
        }
    },
    beforeMount() {
        this.checked = this.roleHasPermission()

        this.$bus.$on('fjPermissionsToggleAll', this.toggle)
    },
    methods: {
        async change(val) {

            let payload = {
                role: this.fjPermissionsRole,
                permission: this.permission.id
            };

            let response = await axios.put('aw-studio/fjord-permissions/role_permissions', payload);

            this.$store.commit('SET_FJ_PERMISSIONS_ROLE_PERMISSIONS', response.data)

            this.$bvToast.toast(
                this.$t('fj.permission_updated', {
                    operation: this.$t(`fj.${this.operation}`),
                    group: this.$t(`permissions.${this.group}`)
                }),
                {
                    variant: 'success'
                }
            );
        },
        toggle({on, group}) {

            if(this.group != group) {
                return
            }

            if(!this.permission) {
                return
            }

            console.log(this.checked, on)
            if(this.checked && on || !this.checked && !on) {
                return
            }

            this.checked = !this.checked
            this.change(this.checked)
        },
        roleHasPermission() {
            if(!this.permission) {
                return false
            }
            return (
                _.size(
                    _.filter(this.fjPermissionsRolePermissions, {
                        role_id: this.fjPermissionsRole.id,
                        permission_id: this.permission.id
                    })
                ) > 0
            );
        },
    },
    computed: {
        ...mapGetters([
            'fjPermissionsRole',
            'fjPermissionsPermissions',
            'fjPermissionsRolePermissions'
        ]),
        group() {
            return this.item.name.split(' ').slice(1).join(' ')
        },
        permissionName() {
            return `${this.operation} ${this.group}`
        },
        operation() {
            return this.col.key
        },
        permission() {
            for(let id in this.fjPermissionsPermissions) {
                let permission = this.fjPermissionsPermissions[id]
                if(permission.name == this.permissionName) {
                    return permission
                }
            }
        }
    }
}
</script>
