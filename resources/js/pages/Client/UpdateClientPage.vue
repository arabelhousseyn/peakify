<template>
    <div  class="update-user-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-btn class="mt-3" @click="$router.push('/home/clients')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>
            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Modifier un client</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="handle">
                        <v-row>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    :disabled="disable"
                                    v-model="infos.full_name"
                                    solo
                                    required
                                    label="Nom complet*"
                                    prepend-inner-icon="mdi-account"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    :disabled="disable"
                                    v-model="infos.phone"
                                    solo
                                    label="Téléphone*"
                                    prepend-inner-icon="mdi-phone"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    :disabled="disable"
                                    v-model="infos.email"
                                    type="email"
                                    solo
                                    label="Email"
                                    prepend-inner-icon="mdi-email"
                                ></v-text-field>
                            </v-col>

                            <v-col v-if="infos.city !== undefined" cols="12" lg="6" sm="6">
                                <v-combobox
                                    :disabled="disable"
                                    v-model="infos.city.name"
                                    solo
                                    :items="cities"
                                    label="Ville"
                                    prepend-inner-icon="mdi-city"
                                ></v-combobox>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    :disabled="disable"
                                    v-model="infos.address"
                                    solo
                                    label="Adresse"
                                    prepend-inner-icon="mdi-google-maps"
                                ></v-text-field>
                            </v-col>

                            <v-alert v-if="hasError" border="right" colored-border type="error" elevation="2">
                                <ul>
                                    <li v-for="(error,index) in errors" :key="index"><span>{{error}}</span></li>
                                </ul>
                            </v-alert>

                            <v-btn :disabled="disabled" text type="submit" color="success">
                                <span v-if="!loading"><v-icon>mdi-account-edit</v-icon> Modifier</span>
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
        <confirmation-update-user-dialog :dialog="dialog" @close="close" @update="update" />
    </div>
</template>
<script>
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent";
import ConfirmationUpdateUserDialog from "../../components/dialog/ConfirmationUpdateUserDialog";
export default {
    props : ['data'],
    data : ()=>({
        client_id : window.location.href.split('/').pop(),
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
                text: 'Modifier un client',
                disabled: true,
                href: '',
            },
        ],
        disabled : false,
        loading : false,
        hasError : false,
        errors : [],
        disable : false,
        infos : {},
        dialog : false,
        fruits : [],
        cities : []
    }),
    components: {ConfirmationUpdateUserDialog, BreadCrumbsComponent},
    methods : {
        handle()
        {
            this.dialog = true
        },
        close()
        {
            this.dialog = false
        },
        update()
        {
            this.dialog = false
            this.loading = true
            this.disabled = true

            let city;
            city = this.fruits.filter(function (fruit){
                return fruit.name == this
            },this.infos.city.name)[0]

            this.infos.city_id = city._id

            if(this.infos.email == null)
            {
                delete this.infos.email
            }

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/client/${this.client_id}`,this.infos).then(e=>{
                    this.$toast.open({
                        message : "Opération effectué",
                        type : 'success'
                    })
                    this.loading = false
                    this.disabled = false
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
                        this.disabled = false
                    }
                })
            })
        },
        init()
        {
            this.disable = true
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/client/client-details/${this.client_id}`).then(e=>{
                    this.infos = e.data.data
                    this.disable = false
                }).catch(err => {
                    window.location.href = '/home/clients'
                })
            })

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get('/api/city/all').then(e=>{
                    this.fruits = e.data.data
                    for (const fruit of this.fruits) {
                        this.cities.push(fruit.name)
                    }
                }).catch(err => {
                    console.log(err)
                })
            })
        }
    },
    mounted() {
        if(this.data == undefined)
        {
            this.init()
        }else{
            this.infos = this.data
        }
    }
}
</script>
