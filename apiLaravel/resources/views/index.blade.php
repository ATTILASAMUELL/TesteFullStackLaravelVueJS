<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.2/dist/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <title>Teste Full Stack - LARAVEL & VUE JS</title>
</head>

<body>
    <div id="app">
        <v-app>
            <v-main>
                <v-card class="mx-auto mt-5" max-width="1300">
                    <br>
                    <v-btn rounded color="green accent-2" @click="createRecord()">Novo Registro</v-btn>

                    <!-- Tabla y formulario -->
                    <v-simple-table class="mt-5">
                        <template v-slot:default>
                            <thead>
                                <tr class="light-blue darken-2">
                                    
                                    <th class="white--text">NOME</th>
                                    <th class="white--text">EMAIL</th>
                                    <th class="white--text">SENHA</th>
                                    <th class="white--text text-center">TELEFONE</th>
                                    <th class="white--text text-center">FOTO</th>
                                    <th class="white--text text-center"></th>
                                    <th class="white--text text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="bodyTabela">
                                <tr v-for="register in registers" :key="register.id">
                        
                                    <td>@{{ register.name }}</td>
                                    <td>@{{ register.email }}</td>
                                    <td>******************</td>
                                    <td>@{{ register.phone }}</td>
                                    <td>
                                        <v-img v-if="register.picture" :src="`http://localhost:8000/images/${register.picture}`" max-height="150" max-width="100"></v-img>
                                    </td>
                                    <td></td>
                                    <td>
                                        <v-btn class="teal accent-4" dark
                                            @click="editRecord(register.id, register.name, register.email, register.phone, register.password, register.picture)">
                                            Editar</v-btn>
                                        <v-btn class="error" dark @click="destroyRecord(register.id)">Deletar</v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table
                </v-card>

                    <!-- Componente de Diálogo para CRIAR E EDITAR -->
                <v-dialog v-model="dialog" max-width="500">
                    <v-card>
                        <v-card-title class="blue-grey darken-1 white--text">Registro 
                        </v-card-title>
                        <br>
                        <v-card-text>
                            <v-form>
                                <v-container>
                                    <v-row>
                                        <v-col cols="20" md="40">
                                            <v-text-field v-model="registro.name" label="Nome" solo required>
                                                @{{registro.name}}</v-text-field>
                                        </v-col>
                                        <v-col cols="20" md="40">
                                            <v-text-field v-model="registro.email" label="Email" solo required>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="20" md="40">
                                            <v-text-field v-model="registro.phone" label="Telefone" solo
                                                required></v-text-field>
                                        </v-col>
                                        <v-col cols="20" md="40">
                                            <v-text-field v-model="registro.password" label="Senha" solo required>
                                            </v-text-field>
                                        </v-col>

                                        <v-col cols="12">
                                            <div v-if="edit">
                                                <div id="divFtAtual">Foto Atual:</div>
                                                <img id="fotoEditar" height="50" width="50"
                                                :src="`http://localhost:8000/images/${registro.picture}`">
                                                <br>
                                                <div id="divFtNova">Foto Nova:</div>
                                            </div>

                                            <input type="file" required id="foto" @change="fileEscolhido" />
                                        </v-col>
                                        <br>
                                        <br>
                                        <v-col cols="12">
                                            <div v-for="error in errors">
                                                <v-alert type="error">@{{error[0]}}</v-alert>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn @click="dialog=false">Cancelar</v-btn>
                            <v-btn @click="storeOrUpdate()" type="submit" color="indigo" dark>@{{this.btnLabel}}</v-btn>
                        </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>
            </v-main>
        </v-app>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"
        integrity="sha512-quHCp3WbBNkwLfYUMd+KwBAgpVukJu5MncuQaWXgCrfgcxCJAq/fo+oqrRKOj+UKEmyMCG3tb8RB63W+EmrOBg=="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.2/dist/sweetalert2.all.min.js"></script>

    <script>
        let url = "http://127.0.0.1:8000/api/registers";

        Vue.config.productionTip = false
        let aplication = new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    registers: [],
                    dialog: false,
                    
                    controlador: false,
                    errors: [],
                    edit: false,

                    tipo: {
                        alerta: "error"
                    },
                   
                    btnLabel: 'Salvar',
                    registro: {
                        name: "",
                        email: "",
                        password: "",
                        phone: "",
                        foto: ""
                    }
                }
            },

            mounted() {
                this.listAllRegisters();
            },

            methods: {

                listAllRegisters: function () {
                    let urlGet = "http://127.0.0.1:8000/api/registers";
                    axios.get(urlGet).then(response => {
                        this.registers = response.data;
                    })
                },


                storeOrUpdate: function () {
                    if (this.edit) {
                        this.updateRegister();
                    } else {
                        this.storeRecord();
                    }
                },

                storeRecord: function(){
                    //
                    let formData = new FormData();

                    formData.append('name', this.registro.name);
                    formData.append('email', this.registro.email);
                    formData.append('phone', parseInt(this.registro.phone));
                    formData.append('password', this.registro.password);

                    let urlPost = "http://127.0.0.1:8000/api/registers/";

                    axios.post(urlPost, formData, {
                        headers: {
                            'Content-type': 'multipart/form-data',
                            'Accept': 'application/json'
                        }
                    }).then(response => {

                        document.getElementById('foto').value = "";
                        this.registers.name = "";
                        this.registers.email = "";
                        this.registers.phone = "";
                        this.registers.password = "";
                        this.dialog = false;
                      
                        Swal.fire({
                            icon: 'success',
                            title: 'Record created.',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        
                        this.listAllRegisters();
                    }).catch((error) => {
                            this.tipo.alerta = "error";
                            this.errors = error.response.data.errors
                           
                        })

                },

                createRecord: function () {
                  
                    this.btnLabel = 'Inserir';
                    this.edit = false;
                    
                    this.errors = [];
                    this.registro.name = "";
                    this.registro.email = "";
                    this.registro.phone = "";
                    this.registro.password = "";
                    this.dialog = true;

                    if (document.getElementById('foto') != null) {
                        document.getElementById('foto').value = "";
                    }
                },

                destroyRecord(id) {
                    Swal.fire({
                        title: 'Deseja excluir o Registro?',
                        confirmButtonText: `Confirmar`,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let urlDel = "http://127.0.0.1:8000/api/registers/" + id;
                            
                            axios.delete(urlDel).then(response => {
                                this.listAllRegisters();
                            })

                            Swal.fire('Excluido com  Sucesso!!!', '', 'success')
                        } else if (result.isDenied) {
                        }

                    });

                },

                editRecord: function (id, nome, email, telefone, senha, foto) {

                    this.btnLabel = 'Atualizar';
                    this.edit=true;
                    this.errors = [];
                    this.dialog = true;
                    
                    this.registro.id = id;
                    this.registro.name = nome;
                    this.registro.email = email;
                    this.registro.phone = telefone;
                    this.registro.password = senha;
                    this.registro.picture = foto;
                   
                },

                

                updateRegister: function () {

                    let urlEdit = "http://localhost:8000/api/registers/" + this.registro.id;
                    
                    axios.put(urlEdit, {

                        name: this.registro.name,
                        email: this.registro.email,
                        phone: this.registro.phone,
                        password: this.registro.password

                    }, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        console.log(response)
                        //this.validacoeRespostaBackEnd(response);
                       

                        this.registers.name = "";
                        this.registers.email = "";
                        this.registers.phone = "";
                        this.registers.password = "";

                        this.dialog = false
                        Swal.fire({
                            icon: 'success',
                            title: 'Record updated.',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        
                        this.listAllRegisters();
                    })
                    .catch((error) => {
                            this.tipo.alerta = "error";
                            this.errors = error.response.data.errors
                        })
                },

                fileEscolhido: function (event) {
                    let file = document.getElementById('foto').files[0];

                    formData.append('foto', file);

                    this.controlador = true;
                }
            }
        })
    </script>

</body>

</html>