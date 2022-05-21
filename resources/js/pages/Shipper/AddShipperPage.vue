<template>
    <div class="add-shipper-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />

            <v-btn class="mt-3" @click="$router.push('/home/shippers')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>

            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Ajouter un livreur</v-card-title>
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
                                    prepend-inner-icon="mdi-square"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.phone"
                                    solo
                                    required
                                    label="Telephone*"
                                    prepend-inner-icon="mdi-phone"
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
                                <v-select
                                    @change="check"
                                    v-model="data.type"
                                    solo
                                    :items="types"
                                    required
                                    label="Type*"
                                ></v-select>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-combobox
                                    @change="mutateValue"
                                    solo
                                    :items="cities"
                                    required
                                    multiple
                                    label="Villes"
                                ></v-combobox>
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
            phone : null,
            email : null,
            type : null,
            cities : []
        },
        items : [
            {
                text: 'Tableau de bord',
                disabled: false,
                href: '/',
            },
            {
                text: 'Livreurs',
                disabled: false,
                href: '/home/shippers',
            },
            {
                text: 'Ajouter un Livreur',
                disabled: false,
                href: '/home/shippers/add-shipper',
            },
        ],
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],
        cities : [],
        fruits : [],
        types : ['Personne','Société']

    }),
    components: {BreadCrumbsComponent},
    methods : {
        store()
        {
            this.loading = true
            this.disabled = true

            this.data.type = (this.data.type == 'Personne') ? 'P' : 'C'

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/shipper',this.data).then(e=>{
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
        mutateValue()
        {

        },
        check()
        {
            if(this.hasError)
            {
                this.hasError = false
                this.errors = []
            }
            this.disabled = (this.data.full_name == null || this.data.phone == null ||
            this.data.type == null || this.data.email == null) ? true : false
        },
        empty()
        {
            this.data.full_name = null
            this.data.phone = null
            this.data.type = null
            this.data.email = null
            this.data.cities = []
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get('/api/city/all').then(e=>{
                    this.fruits = e.data.data
                    this.cities = this.fruits.map(function (fruit){
                        return fruit.name
                    })
                }).catch(err => {
                    console.log(err)
                })
            })
        }
    },
    mounted() {
        this.init()
    }
}
</script>
