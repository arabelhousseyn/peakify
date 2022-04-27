<template>
    <div class="add-client-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />

            <v-btn class="mt-3" @click="$router.push('/home/clients')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>

            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Ajouter un client</v-card-title>
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
                                    v-model="data.phone"
                                    solo
                                    label="Téléphone*"
                                    required
                                    prepend-inner-icon="mdi-phone"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.email"
                                    type="email"
                                    solo
                                    label="Email"
                                    prepend-inner-icon="mdi-email"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.town"
                                    solo
                                    label="Ville"
                                    prepend-inner-icon="mdi-city"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.address"
                                    solo
                                    label="Adresse"
                                    prepend-inner-icon="mdi-google-maps"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <p><span class="font-weight-bold">Note : </span> <span class="grey--text">* indique les champs requis.</span> </p>
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
            phone : null,
        },
        items : [
            {
                text: 'Tableau de bord',
                disabled: false,
                href: '/',
            },
            {
                text: 'Clients',
                disabled: false,
                href: '/home/clients',
            },
            {
                text: 'Ajouter un client',
                disabled: false,
                href: '/home/users/add-client',
            },
        ],
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],

    }),
    components: {BreadCrumbsComponent},
    methods : {
        store()
        {
            this.loading = true
            this.disabled = true
            // because when i define the city directly into v-model binding it say the keyword city is already used in the kernel system of vue
            if(this.data.town)
            {
                this.data.city = this.data.town
                delete this.data.town
            }

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/client',this.data).then(e=>{
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
            this.disabled = (this.data.full_name == null || this.data.phone == null) ? true : false
        },
        empty()
        {
            this.data.full_name = null
            this.data.phone = null
            delete this.data.email
            delete this.data.town
            delete this.data.address

        }
    }
}
</script>
