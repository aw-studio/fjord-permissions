<template>
    <div>
        <span v-if="role != ''">
            {{
                $te(`roles.${role.name}`)
                    ? $t(`roles.${role.name}`).toString()
                    : role.name.capitalize()
            }}
        </span>
    </div>
</template>
<script>
export default {
    name: 'FjordUsersRoleName',
    props: {
        item: {
            required: true,
            type: Object,
        },
    },
    computed: {
        role() {
            return this.item.role ? this.item.role : null;
        },
    },
};
</script>
