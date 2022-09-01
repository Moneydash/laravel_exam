<template>
    <div>
        <v-app-bar
            color="deep-purple accent-4"
            dense
            dark
            >

            <v-toolbar-title>Welcome, {{ $store.state.name }}</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="userListLink"
                    >
                    mdi-account
                    </v-icon>
                </template>
                <span>User List</span>
            </v-tooltip>

            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon
                    style="margin-left: 20px;"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="roleListLink"
                    >
                    mdi-account-cog
                    </v-icon>
                </template>
                <span>User Roles</span>
            </v-tooltip>

            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon
                    style="margin-left: 20px;"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="logout"
                    >
                    mdi-power
                    </v-icon>
                </template>
                <span>Log Out</span>
            </v-tooltip>
        </v-app-bar>
        <router-view></router-view>
    </div>
</template>

<script>
    import { mapGetters, mapState } from 'vuex';
    export default {
        computed: {
            ...mapGetters(['token', 'invoker']),
            ...mapState(['token', 'invoker'])
        },

        beforeCreate() {
            if (localStorage.getItem('app_user_token') == null || this.$store.state.token == null) {
                this.$router.push({ path: '/login' });
            }
        },

        methods: {
            userListLink() {
                this.$router.push({ path: '/user/user_list'});
            },
            logout() {
                this.$http.delete(this.$dir + '/oauth/tokens/' + this.$store.state.token);
                localStorage.clear();
                location.reload();
            },
            roleListLink() {
                this.$router.push({ path: '/user/role_list' });
            }
        }
    }
</script>