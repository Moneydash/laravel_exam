<template>
    <div>
        <v-container style="margin-top: 20px;">
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
            <v-data-table
                :headers="headers"
                :items="rows"
                sort-by="name"
                elevation="3"
                :loading="this.table_loading"
            >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>User List</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-dialog
                        v-model="dialog"
                        max-width="500px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                            >
                            New User
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                            <span class="text-h5">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                            <v-container>
                                <v-row>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    <v-text-field
                                    v-model="user.name"
                                    label="Name"
                                    outlined
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    <v-text-field
                                    v-model="user.email"
                                    label="Email"
                                    outlined
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    <v-text-field
                                    v-model="user.password"
                                    label="New Password"
                                    type="password"
                                    outlined
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    <v-text-field
                                    v-model="user.conf_pass"
                                    label="Confirm Password"
                                    type="password"
                                    outlined
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    <v-select
                                        v-model="user.selected_role"
                                        :items="user.roles"
                                        item-text="role_name"
                                        item-value="value"
                                        label="Roles"
                                        outlined
                                        return-object
                                    ></v-select>
                                </v-col>
                                </v-row>
                            </v-container>
                            </v-card-text>

                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="close"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="save"
                            >
                                Save
                            </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="text-h5">Are you sure you want to delete this User?</v-card-title>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="deleteUserConfirm">OK</v-btn>
                            <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="editUser(item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteUser(item)"
                    >
                        mdi-delete
                    </v-icon>
                    </template>
            </v-data-table>
        </v-container>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dialog: false,
                dialogDelete: false,
                headers: [
                    { text: 'Name', value: 'name'},
                    { text: 'Email', value: 'email' },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                rows: [],
                editIdx: -1,
                table_loading: false,
                user: {
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    conf_pass: '',
                    selected_role: {role_name: 'Admin', value: '1'},
                    roles: [
                        { role_name: 'Admin', value: '1' },
                        { role_name: 'User', value: '2' }
                    ]
                },
                snackbar: {
                    show: false,
                    text: '',
                    timeout: -1
                }
            }
        },

        created() {
            this.init_table();
        },

        computed: {
            formTitle () {
                return this.editIdx === -1 ? 'New User' : 'Edit User'
            }
        },

        methods: {
            async init_table() {
                this.table_loading = true;
                await this.$http({
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('app_user_token'),
                        Accept: 'application/json'
                    },
                    url: this.$dir + '/api/user/getUsers'
                })
                .then(res => {
                    this.rows = res.data
                    this.table_loading = false;
                })
                .catch(e => { console.log(e); });
            },

            async editUser(user) {
                this.user.id = await user.id;
                this.user.name = await user.name;
                this.user.email = await user.email;
                this.dialog = true;
                this.editIdx = 1;
            },

            async deleteUser(user) {
                this.user.id = await user.id;
                this.dialogDelete = true;
            },

            async close() {
                this.dialog = false;
                this.user.name = '';
                this.user.email = '';
                this.user.password = '',
                this.user.conf_pass = '',
                this.editIdx = -1;
            },

            async closeDelete() {
                this.dialogDelete = false;
                this.user.id = '';
            },

            async deleteUserConfirm() {
                this.$http.delete(this.$dir + '/api/user/deletUser/' + this.user.id)
                .then(res => {
                    if (res.data.statusCode == 200) {
                        this.snackbar.show = true;
                        this.snackbar.text = res.data.message;
                        this.snackbar.timeout = 2000;

                        this.closeDelete();
                        this.init_table();
                    }
                })
                .catch(err => { console.log(err); });
            },

            async save() {
                if (this.user.password.length >= 8) {
                    if (this.user.conf_pass == this.user.password) {
                        if (this.editIdx == 1) {
                            await this.$http({
                                method: "POST",
                                url: this.$dir + '/api/user/updateUser/' + this.user.id,
                                data: {
                                    full_name: this.user.name,
                                    email: this.user.email,
                                    password: this.user.password,
                                    role: this.user.selected_role.value
                                }
                            })
                            .then(res => {
                                if (res.data.statusCode == 200) {
                                    this.snackbar.show = true;
                                    this.snackbar.text = res.data.message;
                                    this.snackbar.timeout = 2000;

                                    this.close();
                                    this.init_table();
                                }
                            })
                            .catch(err => { console.log(err); });
                        } else {await this.$http.post(this.$dir + '/api/user/register', {
                                full_name: this.user.name,
                                email: this.user.email,
                                password: this.user.password,
                                role: this.user.selected_role.value
                            })
                            .then(res => {
                                if (res.data.statusCode == 200) {
                                    this.snackbar.show = true;
                                    this.snackbar.text = res.data.message;
                                    this.snackbar.timeout = 2000;

                                    this.close();
                                    this.init_table();
                                }
                            })
                            .catch(err => { console.log(err); })
                        }
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
            }
        }
    }
</script>