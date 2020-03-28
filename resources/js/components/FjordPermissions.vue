<template>
    <fj-base-container>
        <fj-base-header :title="$t('fj.permissions')">

        </fj-base-header>

        <b-row>
            <b-col>
                <fj-index-table
                    :cols="cols"
                    :items="permissions"
                    :count="count"
                    :loadItems="loadPermissions"
                    :searchKeys="['name']"
                    :nameSingular="$t('fj.permissions')"
                    :namePlural="$t('fj.permissions')"
                    :sortBy="config.sortBy"
                    :sortByDefault="config.sortByDefault"
                    :filter="config.filter"
                    :globalActions="config.globalActions"/>
            </b-col>
        </b-row>

        <!--
        <b-card no-body>
            <b-tabs card>
                <b-tab
                    :title="role.name.capitalize()"
                    :active="index == 0"
                    v-for="(role, index) in roles"
                    :key="index"
                >
                    <b-card-text>
                        <table class="table table-hover">
                            <thead>
                                <th>
                                    Model
                                </th>
                                <th
                                    v-for="(c,
                                    index) in role_permission_operations(role)"
                                    :key="index"
                                >
                                    {{ $t('fj.'+c) }}
                                </th>
                                <th>all</th>
                            </thead>
                            <tbody :class="update.updater">
                                <tr
                                    v-for="(group,
                                    index) in role_permission_groups(role)"
                                    :key="index"
                                >
                                    <th scope="row">
                                        {{ $t(`permissions.${group}`) }}
                                    </th>
                                    <td
                                        v-for="(c,
                                        index) in role_permission_operations(
                                            role
                                        )"
                                        :key="index"
                                    >
                                        <b-form-checkbox
                                            v-model="
                                                roles_permissions[role.name][
                                                    `${c} ${group}`
                                                ]
                                            "
                                            @input="
                                                togglePermission(role, c, group)
                                            "
                                            name="check-button"
                                            switch
                                        >
                                        </b-form-checkbox>
                                    </td>
                                    <td>
                                        <b-button
                                            variant="secondary"
                                            size="sm"
                                            @click="toggleAll(role, group)"
                                            >{{ $t('fj.toggle_all') }}</b-button
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
        -->
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
        },
    },
    data() {
        return {
            count: 0,
            permissions: [],
            update: {
                updater: null
            },
            roles_permissions: {},
            crud: ['Create', 'Read', 'Update', 'Delete']
        };
    },
    beforeMount() {
        this.$store.commit('SET_FJ_PERMISSIONS_ROLE', this.roles[0])
        this.$store.commit('SET_FJ_PERMISSIONS_OPERATIONS', this.operations)
        this.$store.commit('SET_FJ_PERMISSIONS_ROLE_PERMISSIONS', this.role_permissions)

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
        async loadPermissions(payload) {
            let response = await axios.post('aw-studio/fjord-permissions/index', payload)
            this.permissions = response.data.unique_items
            this.count = response.data.count

            this.$store.commit('SET_FJ_PERMISSIONS_PERMISSIONS', response.data.items)
        },
        role_permission_groups(role) {
            let keys = Object.keys(this.roles_permissions[role.name]).map(
                key => {
                    return key.split(' ')[1];
                }
            );

            return _.uniq(keys);
        },
        role_permission_operations(role) {
            let keys = Object.keys(this.roles_permissions[role.name]).map(
                key => {
                    return key.split(' ')[0];
                }
            );

            return _.uniq(keys);
        },

        async togglePermission(role, operation, group) {
            let permission_name = `${operation} ${group}`;

            let permission = _.find(this.permissions, [
                'name',
                permission_name
            ]);

            let payload = {
                role,
                permission
            };
            let respose = await axios.put('aw-studio/fjord-permissions/role_permissions', payload);

            this.$bvToast.toast(
                this.$t('fj.permission_updated', {
                    operation: this.$t(`fj.${operation}`),
                    group: this.$t(`permissions.${group}`)
                }),
                {
                    variant: 'success'
                }
            );
        },
    }
};
</script>
