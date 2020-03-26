require("./service/component.service");
require("./common/mixins");

import permissions from './store/permissions.module';

export default {
    name: "FjordPermissions",
    store: {
        permissions
    },
    props: {
        permissions: {
            required: true,
            type: Array
        }
    },
    beforeMount(app) {
        app.$store.commit('SET_PERMISSIONS', app.$attrs.permissions);
    }
};
