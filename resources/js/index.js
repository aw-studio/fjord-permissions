require('./service/component.service');
require('./common/mixins');

import permissions from './store/permissions.module';

export default {
	name: 'FjordPermissions',
	store: {
		permissions
	},
	extensions: {
		['fj-users'](app, props) {
			app.$store.commit('SET_ROLES', props.roles);
		}
	},
	beforeMount(app) {
		app.$store.commit('SET_PERMISSIONS', app.$attrs.permissions);
	}
};
