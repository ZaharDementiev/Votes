<template>
    <div v-if="notifications !== 0" class="count_activity">
        <span>{{ notifications }}</span>
    </div>
</template>

<script>
    export default {
        name: "UnapprovedCount",
        data() {
            return {
                notifications: 0,
            }
        },

        methods: {
            countNotifications() {
                axios.get('/countUnapproved').then(response => {
                    this.notifications = response.data;
                });

            }
        },

        created () {
            this.countNotifications();
        },

    }
</script>
