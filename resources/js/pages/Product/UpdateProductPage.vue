<template>
    <div class="update-product-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-btn class="mt-3" @click="$router.push('/home/products')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>
            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Modifier un produit</v-card-title>
                <v-card-text>
                    <form v-if="infos !== undefined" class="flex justify-content-center mb-3" @submit.prevent="handle">
                        <v-row>
                            <v-col cols="12">
                                <v-select
                                    :items="categories"
                                    v-model="infos.category.name"
                                    label="Choisir catégorie"
                                    dense
                                    solo
                                ></v-select>
                                <v-chip dark color="green">{{infos.category.name}}</v-chip>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    label="Code produit"
                                    v-model="infos.product_code"
                                    prepend-inner-icon="mdi-code-array"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    v-model="infos.product_name"
                                    label="Nom produit"
                                    prepend-inner-icon="mdi-tshirt-crew-outline"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-textarea
                                    solo
                                    v-model="infos.description"
                                    label="Description"
                                    prepend-inner-icon="mdi-format-text-variant-outline"
                                ></v-textarea>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    v-model="infos.priceValue"
                                    label="Prix"
                                    prepend-inner-icon="mdi-currency-usd"
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
        product_id : window.location.href.split('/').pop(),
        categories: [],
        fruits : [],
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
                text: 'Modifier un produit',
                disabled: true,
                href: '',
            },
        ],
        disabled : false,
        loading : false,
        hasError : false,
        errors : [],
        disable : false,
        infos : undefined,
        dialog : false,
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

            let filter = this.fruits.filter(function (fruit){
                return fruit.name == this
            },this.infos.category.name)[0]

            this.infos.category_id = filter._id
            this.infos.price = this.infos.priceValue
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/product/${this.product_id}`,this.infos).then(e=>{
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
                axios.get(`/api/product/product-details/${this.product_id}`).then(e=>{
                    this.infos = e.data.data
                    this.disable = false
                }).catch(err => {
                    window.location.href = '/home/products'
                })
            })
            this.initCategories()
        },
        initCategories()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get('/api/category/all').then(e=>{
                    this.fruits = e.data.data
                    this.categories = this.fruits.map(function(fruit){
                        return fruit.name
                    })
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
            this.initCategories()
        }
    }
}
</script>
