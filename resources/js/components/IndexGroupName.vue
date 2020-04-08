<template>
    <span>
        {{
            this.$te(`permissions.${group}`)
                ? this.$t(`permissions.${group}`)
                : group.capitalize()
        }}
    </span>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
    name: 'IndexGroupName',
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
    computed: {
        group() {
            return this.item.name
                .split(' ')
                .slice(1)
                .join(' ');
        }
    }
};
</script>
