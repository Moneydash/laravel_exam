<template>
    <v-app>

        <v-snackbar v-model="snackbar.show" :timeout="snackbar.timeout" top>
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
                        <v-card-title><h3 class="h3">Sign Up</h3><v-icon right>mdi-account-plus</v-icon></v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="mt-3">
                            <v-text-field outlined label="Full Name" v-model="user_info.full_name" autofocus></v-text-field>
                            <v-text-field outlined label="Email" v-model="user_info.email"></v-text-field>
                            <v-text-field outlined label="Password" type="password" v-model="user_info.password"></v-text-field>
                            <v-text-field outlined label="Confirm Password" type="password" v-model="user_info.conf_pass"></v-text-field>
                            <v-select
                                v-model="user_info.selected_role"
                                :items="user_info.roles"
                                item-text="role_name"
                                item-value="value"
                                label="Roles"
                                outlined
                            ></v-select>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions class="my-3">
                            <v-spacer></v-spacer>
                            <v-btn color="primary" class="mr-2" @click="register">Sign Up <v-icon right>mdi-account-plus</v-icon></v-btn>
                        </v-card-actions>
                    </v-card>
                </v-row>
                <v-row class="justify-center">
                    <p class="mt-3">Already have an account? <a href="/login" style="text-decoration: none;" class="ml-1">Sign In</a></p>
                </v-row>
            </v-col>
        </v-container>
    </v-app>
</template>

<script>

export default {
    name: 'Register',
    
    data() {
        return {
            user_info: {
                full_name: '',
                email: '',
                password: '',
                conf_pass: '',
                selected_role: { role_name: 'Admin', id: '1' },
                roles: [],
            },
            snackbar: {
                show: false,
                text: '',
                timeout: -1
            }
        }
    },

    created() {
        this.getRoles();
    },

    methods: {
        register() {
            if (this.user_info.password.length >= 8) {
                if (this.user_info.conf_pass == this.user_info.password) {
                    this.$http({
                        method: "POST",
                        data: {
                            full_name: this.user_info.full_name,
                            email: this.user_info.email,
                            password: this.user_info.password,
                            role: this.user_info.selected_role.id
                        },
                        url: this.$dir + '/api/user/register'
                    })
                    .then(res => {
                        if (res.data.statusCode == 200) {
                            this.snackbar.show = true;
                            this.snackbar.text = res.data.message;
                            this.snackbar.timeout = 1600;

                            var snack = this.snackbar;
                            var routerr = this.$router;

                            setTimeout(function() {
                                if (snack.show == false) {
                                    routerr.push({ path: '/login' });
                                }
                            }, 1650);
                        }
                    })
                    .catch(err => { console.log(err); })
                } else {
                    this.snackbar.show = true;
                    this.snackbar.text = 'Password don\'t match!';
                    this.snackbar.timeout = 1600;
                }
            } else {
                this.snackbar.show = true;
                this.snackbar.text = 'Password is too short!';
                this.snackbar.timeout = 1600;
            }
        },

        async getRoles() {
            this.$http.get(this.$dir + '/api/role/getRoles')
            .then(res => {
                this.user_info.roles = res.data
            })
            .catch(err => { console.log(data); });
        }
    }
}
</script>