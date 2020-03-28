import store from '@fj-js/store';
import Vue from 'vue'

Vue.mixin({
    methods: {
        // check if the authenticated user has a permission
        can(permission) {
            return store.getters.permissions.includes(permission)
        }
    }
});
