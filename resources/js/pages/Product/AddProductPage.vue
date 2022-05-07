<template>
    <div class="add-client-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />

            <v-btn class="mt-3" @click="$router.push('/home/products')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>

            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Ajouter un produit</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="store">
                        <v-row>
                            <v-col cols="12">
                                <v-select
                                    :items="categories"
                                    label="Choisir catégorie*"
                                    dense
                                    solo
                                ></v-select>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    label="Code produit*"
                                    prepend-inner-icon="mdi-code-array"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    label="Nom produit*"
                                    prepend-inner-icon="mdi-tshirt-crew-outline"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-textarea
                                    solo
                                    label="Description"
                                    prepend-inner-icon="mdi-format-text-variant-outline"
                                ></v-textarea>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    label="Prix*"
                                    prepend-inner-icon="mdi-currency-usd"
                                ></v-text-field>
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
            name : null,
        },
        categories: [],
        items : [
            {
                text: 'Tableau de bord',
                disabled: false,
                href: '/',
            },
            {
                text: 'Produits',
                disabled: false,
                href: '/home/products',
            },
            {
                text: 'Ajouter un produit',
                disabled: false,
                href: '/home/products/add-product',
            },
        ],
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],
        fruits : []

    }),
    components: {BreadCrumbsComponent},
    methods : {
        store()
        {
            this.loading = true
            this.disabled = true

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/product',this.data).then(e=>{
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
            this.disabled = (this.data.name == null) ? true : false
        },
        empty()
        {
            this.data.name = null
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get('/api/category/all',this.data).then(e=>{
                    this.fruits = e.data.data
                    this.categories = this.fruits.map(function(fruit){
                        return fruit.name
                    })
                })
            })
        }
    },
    mounted() {
        this.init()
    }
}
</script>
