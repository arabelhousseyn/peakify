<template>
    <div class="change-user-password-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />

            <v-btn class="mt-3" @click="$router.push('/home/users')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>

            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Modifier mot de passe</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="changePassword">
                        <v-row>
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





                            <v-alert v-if="hasError" border="right" colored-border type="error" elevation="2">
                                <ul>
                                    <li v-for="(error,index) in errors" :key="index"><span>{{error}}</span></li>
                                </ul>
                            </v-alert>

                            <v-btn :disabled="disabled" text type="submit" color="success">
                                <span v-if="!loading"><v-icon>mdi-user-pencil</v-icon> Modifier</span>
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
                text: 'Changer mot de passe',
                disabled: true,
                href: '/home/users/change-password-user',
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
        changePassword()
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
