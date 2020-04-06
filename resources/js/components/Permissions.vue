<template>
    <fj-base-container>
        <fj-base-header :title="$t('fj.permissions')">
            <div slot="actions-right">
                <fj-permissions-role-create @created="addRole" />
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
                >
                    <template v-slot:header="{ tab }">
                        <div class="mb-3 d-flex justify-content-between">
                            <div></div>
                            <div>
                                <fj-permissions-role-delete
                                    :tab="tab"
                                    :cantDeleteRoleIds="cantDeleteRoleIds"
                                    @deleted="removeRole(tab)"
                                />
                            </div>
                        </div>
                    </template>
                </fj-index-table>
            </b-col>
        </b-row>
    </fj-base-container>
</template>

<script>
export default {
    name: 'Permissions',
    props: {
        cols: {
            type: Array,
            required: true,
        },
        operations: {
            type: Array,
            required: true,
        },
        roles: {
            type: Array,
        },
        role_permissions: {
            type: Array,
        },
        buttons: {
            type: Array,
        },
        config: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            showCreateRole: false,
            count: 0,
            permissions: [],
            roleNames: [],
            update: {
                updater: null,
            },
            roles_permissions: {},
            crud: ['Create', 'Read', 'Update', 'Delete'],
        };
    },
    beforeMount() {
        this.$store.commit('SET_FJ_PERMISSIONS_OPERATIONS', this.operations);
        this.$store.commit(
            'SET_FJ_PERMISSIONS_ROLE_PERMISSIONS',
            this.role_permissions
        );

        this.setRoleNames();

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
    computed: {
        cantDeleteRoleIds() {
            let ids = [];
            for (let i = 0; i < this.roles.length; i++) {
                let role = this.roles[i];
                if (!role) {
                    continue;
                }
                if (['admin', 'user'].includes(role.name)) {
                    ids.push(role.id);
                }
            }
            return ids;
        },
    },
    methods: {
        addRole(role) {
            this.roles.push(role);
            this.setRoleNames();
        },
        removeRole(tab) {
            for (let i = 0; i < this.roles.length; i++) {
                let role = this.roles[i];
                if (role.id != tab.id) {
                    continue;
                }
                delete this.roles[i];
            }
            this.setRoleNames();
        },
        setRoleNames() {
            this.roleNames = [];
            for (let i = 0; i < this.roles.length; i++) {
                let role = this.roles[i];
                if (!role) {
                    continue;
                }
                let tKey = `roles.${role.name}`;
                this.roleNames.push({
                    id: role.id,
                    title: this.$te(tKey)
                        ? this.$t(tKey).toString()
                        : role.name.capitalize(),
                });
            }
        },
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
            let response = await axios.post('index', payload);
            this.permissions = response.data.unique_items;
            this.count = response.data.count;

            this.$store.commit(
                'SET_FJ_PERMISSIONS_PERMISSIONS',
                response.data.items
            );
        },
    },
};
</script>
