<template>
    <div class="data-table-client-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-card class="mt-5" elevation="0">
                <v-card-title>
                    <span>Clients</span>
                    <v-spacer></v-spacer>
                    <v-btn @click="$router.push('users/add-user')" color="primary">
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
                                        <v-list-item link @click="$router.push({name : 'updateUser',params : {id : item._id,data : item}})">
                                            <v-list-item-icon><v-icon color="green">mdi-pencil</v-icon></v-list-item-icon>
                                            <v-list-item-content><v-list-item-title>Modifier</v-list-item-title></v-list-item-content>
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
    </div>
</template>
<script>
import moment from 'moment'
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent";
export default {
    data : ()=>({
        search : null,
        page: 1,
        itemsPerPage : 0,
        count: 0,
        loading : true,
        headers: [
            {
                text: 'Nom complet',
                align: 'start',
                sortable: false,
                value: 'full_name',
            },
            { text: 'email', value: 'email' },
            { text: 'Telephone', value: 'phone' },
            { text: 'Ville', value: 'city' },
            { text: 'Adresse', value: 'address' },
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
                text: 'Clients',
                disabled: false,
                href: '/home/clients',
            },
        ],
        selections: ['Tous les clients','Clients supprimer'],

        dialog1 : false,
        dialog2 : false,
        dialog3 : false,
        dialog4 : false,
        dialog5 : false,
        user_id : null,
        start_at : null,
        end_at : null,
        hint : 'Clients Active'
    }),
    components: {BreadCrumbsComponent},
    methods : {
        filter()
        {
            if(this.hint == 'Clients Active')
            {
                this.init()
            }else if(this.hint == 'Tous les clients')
            {
                this.callApi(0)
            }else if (this.hint == 'Clients supprimer')
            {
                this.callApi(1)
            }
        },
        callApi(filter)
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/client/filter/${filter}?page=${this.page}`).then(e=>{
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
            if(this.hint == 'Clients Active')
            {
                this.init()
            }else{
                this.filter()
            }
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/client?page=${this.page}`).then(e=>{
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
        lock(id)
        {
            this.user_id = id
            this.dialog1 = true
        },
        unlock(id)
        {
            this.user_id = id
            this.dialog2 = true
        },
        hours(id,start_at,end_at)
        {
            this.user_id = id
            this.start_at = start_at
            this.end_at = end_at
            this.dialog3 = true
        },
        destroy(id)
        {
            this.user_id = id
            this.dialog4 = true
        },
        restore(id)
        {
            this.user_id = id
            this.dialog5 = true
        },
        close()
        {
            this.user_id = null
            this.dialog1 = false
        },
        close1()
        {
            this.user_id = null
            this.dialog2 = false
        },
        close2()
        {
            this.user_id = null
            this.start_at = null
            this.end_at = null
            this.dialog3 = false
        },
        close3()
        {
            this.user_id = null
            this.dialog4 = false
        },
        close4()
        {
            this.user_id = null
            this.dialog5 = false
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
