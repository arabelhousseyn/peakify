<template>
    <div class="data-table-product-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-card class="mt-5" elevation="0">
                <v-card-title>
                    <span>Produits</span>
                    <v-spacer></v-spacer>
                    <v-btn @click="$router.push('products/add-product')" color="primary">
                        <v-icon>mdi-plus</v-icon> Ajouter
                    </v-btn>
                </v-card-title>

                <v-card-text>
                    <v-row class="mb-3">
                        <v-col cols="12" lg="10" sm="10">
                            <v-combobox
                                v-model="hint"
                                :items="selections"
                                hide-selected
                                label="Filtrage"
                                persistent-hint
                                small-chips
                                @change="filter"
                            />
                        </v-col>
                        <v-col cols="12" lg="2" sm="2">
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Rechercher"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-data-table
                        :loading="loading"
                        loading-text="Chargement... veuillez patienter"
                        :headers="headers"
                        :items="data"
                        :page.sync="page"
                        :search="search"
                        :items-per-page="itemsPerPage"
                        hide-default-footer
                        class="elevation-1"
                        @page-count="pageCount = $event"
                    >

                        <template v-slot:item.deleted_at="{ item }">
                            <div v-if="item.deleted_at">
                                <v-chip
                                    v-if="item.deleted_at !== null"
                                    color="red"
                                    dark
                                >
                                    Supprimer
                                </v-chip>

                                <v-chip
                                    v-else
                                    color="green"
                                    dark
                                >
                                    Active
                                </v-chip>
                            </div>
                            <div v-else>
                                <v-chip
                                    color="green"
                                    dark
                                >
                                    Active
                                </v-chip>
                            </div>
                        </template>



                        <template v-slot:item.created_by="{ item }">
                            <div class="created-by">
                                <span>{{item.created_by.full_name}}</span>
                                <v-chip v-if="item.created_by.type == 'admin'" dark color="green">
                                    {{item.created_by.type}}
                                </v-chip>
                                <v-chip v-if="item.created_by.type == 'agent'" dark color="yellow">
                                    {{item.created_by.type}}
                                </v-chip>
                            </div>
                        </template>

                        <template v-slot:item.created_at="{ item }">
                            <span> {{ formatDate(item.created_at) }} </span>
                        </template>

                        <template v-slot:item.actions="{ item }">
                            <v-menu
                                bottom
                                min-width="200"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        dark
                                        color="primary"
                                        fab
                                        small
                                        text
                                        v-bind="attrs"
                                        v-on="on"
                                    >
                                        <v-icon>mdi-dots-vertical</v-icon>
                                    </v-btn>
                                </template>

                                <v-list>
                                    <v-list-item-group>
                                        <v-list-item link @click="$router.push({name : 'updateProduct',params : {id : item._id,data : item}})">
                                            <v-list-item-icon><v-icon color="green">mdi-pencil</v-icon></v-list-item-icon>
                                            <v-list-item-content><v-list-item-title>Modifier</v-list-item-title></v-list-item-content>
                                        </v-list-item>
                                        <v-list-item link @click="$router.push(`products/offers/${item._id}`)">
                                            <v-list-item-icon><v-icon color="green">mdi-percent-outline</v-icon></v-list-item-icon>
                                            <v-list-item-content><v-list-item-title>Offres</v-list-item-title></v-list-item-content>
                                        </v-list-item>
                                        <v-list-item link @click="$router.push(`products/variants/${item._id}`)">
                                            <v-list-item-icon><v-icon color="green">mdi-tshirt-crew</v-icon></v-list-item-icon>
                                            <v-list-item-content><v-list-item-title>variantes</v-list-item-title></v-list-item-content>
                                        </v-list-item>
                                        <v-list-item v-if="item.deleted_at == null" link @click="destroy(item._id)">
                                            <v-list-item-icon><v-icon color="red">mdi-trash-can</v-icon></v-list-item-icon>
                                            <v-list-item-content><v-list-item-title>Supprimer</v-list-item-title></v-list-item-content>
                                        </v-list-item>
                                        <v-list-item v-else link @click="restore(item._id)">
                                            <v-list-item-icon><v-icon color="green">mdi-restore</v-icon></v-list-item-icon>
                                            <v-list-item-content><v-list-item-title>Restorer</v-list-item-title></v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-menu>
                        </template>
                    </v-data-table>
                    <div class="text-center pt-2">
                        <v-pagination
                            v-model="page"
                            :length="count"
                            @input="currentPage"
                        ></v-pagination>
                    </div>
                </v-card-text>
            </v-card>
        </v-container>
        <delete-product-dialog @close="close" :dialog="dialog1" :product_id="product_id" />
        <restore-product-dialog @close="close1" :dialog="dialog2" :product_id="product_id" />
    </div>
</template>
<script>
import moment from 'moment'
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent"
import DeleteProductDialog from "../../components/dialog/Product/DeleteProductDialog";
import RestoreProductDialog from "../../components/dialog/Product/RestoreProductDialog";
export default {
    data : ()=>({
        search : null,
        page: 1,
        itemsPerPage : 0,
        count: 0,
        loading : true,
        headers: [
            {
                text: 'Catégorie',
                align: 'start',
                sortable: false,
                value: 'category.name',
            },
            { text: 'Code produit', value: 'product_code' },
            { text: 'Nom produit', value: 'product_name' },
            { text: 'Description', value: 'description' },
            { text: 'Prix', value: 'price' },
            { text: 'Créé par', value: 'created_by' },
            { text: 'état', value: 'deleted_at' },
            { text: 'Créé à', value: 'created_at' },
            { text: 'Actions', value: 'actions', sortable: false },
        ],
        data: [],
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
        ],
        selections: ['Tous les produits','Produits Active','Produits supprimer'],

        dialog1 : false,
        dialog2 : false,
        product_id : null,
        hint : 'Produits Active'
    }),
    components: {RestoreProductDialog, DeleteProductDialog, BreadCrumbsComponent},
    methods : {
        filter()
        {
            if(this.hint == 'Produits Active')
            {
                this.init()
            }else if(this.hint == 'Tous les produits')
            {
                this.callApi(0)
            }else if (this.hint == 'Produits supprimer')
            {
                this.callApi(1)
            }
        },
        callApi(filter)
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/product/filter/${filter}?page=${this.page}`).then(e=>{
                    this.count = e.data.last_page
                    this.itemsPerPage = e.data.per_page
                    this.data = e.data.data
                    this.loading = false
                }).catch(err=>{
                    if(err.response.status == 401)
                    {
                        this.$toast.open({
                            message : err.response.data.message,
                            type : 'error'
                        })
                        this.$store.commit('SET_OUT')
                        this.$router.push('/')
                    }
                })
            })
        },
        formatDate(date)
        {
            return new moment(date).locale('fr').format('MMMM Do YYYY, h:mm:ss a')
        },
        currentPage()
        {
            this.loading = true
            this.data = []
            this.$router.push(`?page=${this.page}`).catch(err => {})
            if(this.hint == 'Produits Active')
            {
                this.init()
            }else{
                this.filter()
            }
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/product?page=${this.page}`).then(e=>{
                    this.count = e.data.last_page
                    this.itemsPerPage = e.data.per_page
                    this.data = e.data.data
                    this.loading = false
                }).catch(err=>{
                    if(err.response.status == 401)
                    {
                        this.$toast.open({
                            message : err.response.data.message,
                            type : 'error'
                        })
                        this.$store.commit('SET_OUT')
                        this.$router.push('/')
                    }
                })
            })
        },
        searchPageUrl()
        {
            return (window.location.search.length == 0) ? 1 : parseInt(window.location.search.replace('?page=',''))
        },
        destroy(id)
        {
            this.product_id = id
            this.dialog1 = true
        },
        restore(id)
        {
            this.product_id = id
            this.dialog2 = true
        },
        close()
        {
            this.product_id = null
            this.dialog1 = false
        },
        close1()
        {
            this.product_id = null
            this.dialog2 = false
        }
    },
    mounted() {
        if(this.searchPageUrl() > 1)
        {
            this.page = this.searchPageUrl()
            this.$router.push(`?page=${this.page}`).catch(err => {})
        }
        this.init()
    }
}
</script>
