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
                :loading="this.role_table_loading"
            >
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title>Role List</v-toolbar-title>
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
                                New Role
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
                                        sm="12"
                                        md="12"
                                    >
                                        <v-text-field
                                        v-model="role.role_name"
                                        label="Role Name"
                                        ></v-text-field>
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
                                <v-btn color="blue darken-1" text @click="deleteRoleConfirm">OK</v-btn>
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
                        @click="editRole(item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteRole(item)"
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
                    { text: 'Role ID', value: 'id' },
                    { text: 'Role Name', value: 'role_name' },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                rows: [],
                role_table_loading: false,
                editIdx: -1,
                role: {
                    id: '',
                    role_name: ''
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
                await this.$http.get(this.$dir + '/api/role/getRoles')
                .then(res => {
                    this.rows = res.data
                })
                .catch(err => { console.log(err); })
            },

            async editRole(role) {
                this.editIdx = 1;
                this.role.id = await role.id;
                this.role.role_name = await role.role_name;
                this.dialog = true;
            },

            async deleteRole(role) {
                this.role.id = await role.id;
                this.dialogDelete = true;
            },

            async close() {
                this.role.id = '',
                this.role.role_name = '';
                this.dialog = false;
            },

            async closeDelete() {
                this.dialogDelete = false;
                this.role.id = '';
            },
            
            async deleteRoleConfirm() {
                this.$http.delete(this.$dir + '/api/role/deleteRole/' + this.role.id)
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
                if (this.role.role_name != '') {
                    if (this.editIdx == -1) {
                        this.$http.post(this.$dir + '/api/role/addRole', {
                            role_name: this.role.role_name
                        })
                        .then(res => {
                            this.snackbar.show = true;
                            this.snackbar.text = res.data.message;
                            this.snackbar.timeout = 2000;

                            this.close();
                            this.init_table();
                        })
                        .catch(err => { console.log(err); });
                    } else {
                        this.$http({
                            method: "POST",
                            url: this.$dir + '/api/role/editRole/' + this.role.id,
                            data: {
                                role_name: this.role.role_name
                            }
                        })
                        .then(res => {
                            this.snackbar.show = true;
                            this.snackbar.text = res.data.message;
                            this.snackbar.timeout = 2000;

                            this.close();
                            this.init_table();
                        })
                        .catch(err => { console.log(err); });
                    }
                } else {
                    this.snackbar.show = true;
                    this.snackbar.text = 'Role Name is empty!';
                    this.snackbar.timeout = 2000;
                }
            }
        }
    }
</script>