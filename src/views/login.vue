<template>
    <v-app>

        <v-snackbar v-model="snackbar.show" :timeout="snackbar.timeout">
            {{ snackbar.text }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="red darken-1"
                    text
                    v-bind="attrs"
                    @click="snackbar.show = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <v-container fill-height fluid>
            <v-col cols="12">
                <v-row align="center" justify="center">
                    <v-card width="450">
                        <v-card-title>
                            <h3 class="h3">Log In</h3><v-icon right>mdi-lock</v-icon>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="mt-3">
                            <v-text-field outlined label="Email or Username" v-model="user.email" autofocus></v-text-field>
                            <v-text-field outlined label="Password" v-model="user.password" type="password"></v-text-field>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions class="my-3">
                            <v-spacer></v-spacer>
                            <v-btn color="success" class="mr-2" @click.prevent="login">Login <v-icon right>mdi-key</v-icon></v-btn>
                        </v-card-actions>
                    </v-card>
                </v-row>
                <v-row class="justify-center">
                    <p class="mt-3">Not yet registered? <a href="/register" style="text-decoration: none;" class="ml-1">Sign Up</a></p>
                </v-row>
            </v-col>
            
        </v-container>
    </v-app>
</template>

<script>

export default {
    name: 'Login',

    data() {
        return {
            user: {
                email: '',
                password: ''
            },
            snackbar: {
                text: '',
                show: false,
                timeout: -1
            }
        }
    },

    beforeCreate() {
        if (localStorage.getItem('app_user_token') != null) {
            this.$router.push({ path: '/home' });
        }
    },

    methods: {
        login() {
            var login_url = this.$http.post(this.$dir + '/oauth/token', {
                grant_type: 'password',
                client_id: '97288966-f1aa-4d88-ab93-5dcb3d9b827a',
                username: this.user.email,
                password: this.user.password,
                client_secret: 'xh2ArbbkyfjjsFNdaFL5jSdU3o3HjLIJScwX83uz'
            });
            var user_url = this.$http({
                method: "GET",
                url: this.$dir + '/api/user/getUser/' + this.user.email
            });

            Promise.all([login_url, user_url])
            .then(response => {
                if (response[0].data.access_token) {
                    this.snackbar.show = true;
                    this.snackbar.text = 'Successfully logged in!';
                    this.snackbar.timeout = 1600;

                    var snack = this.snackbar;
                    var routerr = this.$router;
                    var store = this.$store;
                    var user = response[1].data.data;
                    var data = response[0].data;

                    setTimeout(function() {
                        if (snack.show == false) {
                            routerr.push({ path: '/home' });
                            localStorage.setItem('app_user_token', data.access_token);
                            localStorage.setItem('app_user_refresh_token', data.refresh_token);
                            localStorage.setItem('user', user.email);
                            localStorage.setItem('name', user.name);
                            store.dispatch('token', data.access_token);
                            store.dispatch('invoker', user.email);
                            store.dispatch('nameInvoker', user.name);
                        }
                    }, 1700)
                }
            })
            .catch(err => {
                if (err) {
                    console.log(err);
                    // if (err.response.data.error == 'invalid_grant') {
                    //     this.snackbar.show = true;
                    //     this.snackbar.text = err.response.data.message;
                    //     this.snackbar.timeout = 2500;
                    // }
                }
            });
        }
    }
}
</script>
