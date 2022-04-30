<template>
    <div class="add-user-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />

            <v-btn class="mt-3" @click="$router.push('/home/users')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>

            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Ajouter un utilisateur</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="store">
                        <v-row>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.full_name"
                                    solo
                                    required
                                    label="Nom complet*"
                                    prepend-inner-icon="mdi-account"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.username"
                                    solo
                                    required
                                    label="Nom d'utilisateur*"
                                    prepend-inner-icon="mdi-account"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.email"
                                    type="email"
                                    solo
                                    required
                                    label="Email*"
                                    prepend-inner-icon="mdi-email"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.phone"
                                    solo
                                    label="Téléphone"
                                    prepend-inner-icon="mdi-phone"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.password"
                                    hint="Au moins 8 caractères"
                                    counter
                                    @click:append="show1 = !show1"
                                    :type="show1 ? 'text' : 'password'"
                                    solo
                                    required
                                    label="Mot de passe*"
                                    :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.password_confirmation"
                                    hint="Au moins 8 caractères"
                                    counter
                                    @click:append="show2 = !show2"
                                    :type="show2 ? 'text' : 'password'"
                                    solo
                                    required
                                    label="Confirmation mot de passe*"
                                    :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.job"
                                    solo
                                    label="Fonction"
                                    prepend-inner-icon="mdi-pencil"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">

                                <v-menu
                                    ref="menu"
                                    v-model="menu2"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="data.start_at"
                                            label="Commencer à"
                                            prepend-icon="mdi-clock-time-four-outline"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="menu2"
                                        v-model="data.start_at"
                                        full-width
                                        scrollable
                                        @click:minute="$refs.menu.save(time)"
                                    ></v-time-picker>
                                </v-menu>

                            </v-col>
                            <v-col cols="12" lg="6" sm="6">

                                <v-menu
                                    ref="menu"
                                    v-model="menu3"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="data.end_at"
                                            label="Fini à"
                                            prepend-icon="mdi-clock-time-four-outline"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="menu3"
                                        v-model="data.end_at"
                                        full-width
                                        @click:minute="$refs.menu.save(time)"
                                    ></v-time-picker>
                                </v-menu>

                            </v-col>

                            <v-col cols="12">
                                <small><span class="font-weight-bold">Note : </span> <span class="grey--text">* indique les champs requis.</span> </small>
                            </v-col>

                            <v-alert v-if="hasError" border="right" colored-border type="error" elevation="2">
                                <ul>
                                    <li v-for="(error,index) in errors" :key="index"><span>{{error}}</span></li>
                                </ul>
                            </v-alert>

                            <v-btn :disabled="disabled" text type="submit" color="success">
                                <span v-if="!loading"><v-icon>mdi-plus</v-icon> Ajouter</span>
                                <v-progress-circular
                                    v-else
                                    indeterminate
                                ></v-progress-circular>
                            </v-btn>
                        </v-row>
                    </form>
                </v-card-text>
            </v-card>

        </v-container>
    </div>
</template>
<script>
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent";
export default {
    data : ()=>({
        data : {
            full_name : null,
            email : null,
            username : null,
            password : null,
            password_confirmation : null,
        },
        items : [
            {
                text: 'Tableau de bord',
                disabled: false,
                href: '/',
            },
            {
                text: 'Utilisateurs',
                disabled: false,
                href: '/home/users',
            },
            {
                text: 'Ajouter un utilisateur',
                disabled: false,
                href: '/home/users/add-user',
            },
        ],
        show1 : false,
        show2 : false,
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],
        menu2: false,
        menu3: false,
        time: null,

    }),
    components: {BreadCrumbsComponent},
    methods : {
        store()
        {
            this.loading = true
            this.disabled = true
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/user',this.data).then(e=>{
                    this.$toast.open({
                        message : "Opération effectué",
                        type : 'success'
                    })
                    this.loading = false
                    this.empty()
                }).catch(err => {
                    if(err.response.status == 422)
                    {
                        let errors = err.response.data.errors
                        let values = Object.values(errors)
                        for (let i = 0;i<values.length;i++)
                        {
                            this.errors.push(values[i][0])
                        }
                        this.hasError = true
                        this.loading = false
                    }
                })
            })
        },
        check()
        {
            if(this.hasError)
            {
                this.hasError = false
                this.errors = []
            }
            this.disabled = (this.data.full_name == null || this.data.email == null || this.data.username == null
             || this.data.password == null || this.data.password_confirmation == null) ? true : false
        },
        empty()
        {
            this.data.password_confirmation = null
            this.data.password = null
            this.data.email = null
            this.data.username = null
            this.data.full_name = null
            delete this.data.phone
            delete this.data.job
            delete this.data.start_at
            delete this.data.end_at

        }
    }
}
</script>
