<template>
    <div class="data-table-category-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-card class="mt-5" elevation="0">
                <v-card-title>
                    <span>Canaux</span>
                    <v-spacer></v-spacer>
                    <v-btn @click="$router.push('categories/add-channel')" color="primary">
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
                                        <v-list-item link @click="$router.push({name : 'updateCategory',params : {id : item._id,data : item}})">
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
        <destroy-channel-dialog @close="close" :channel_id="channel_id" :dialog="dialog1" />

    </div>
</template>
<script>
import moment from 'moment'
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent"
import DestroyChannelDialog from "../../components/dialog/Channel/DestroyChannelDialog";
export default {
    data : ()=>({
        search : null,
        page: 1,
        itemsPerPage : 0,
        count: 0,
        loading : true,
        headers: [
            {
                text: 'Nom',
                align: 'start',
                sortable: false,
                value: 'name',
            },
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
                text: 'Canaux',
                disabled: false,
                href: '/home/channels',
            },
        ],
        selections: ['Tous les canaux','Canaux Active','Canaux supprimer'],

        dialog1 : false,
        dialog2 : false,
        channel_id : null,
        hint : 'Canaux Active'
    }),
    components: {DestroyChannelDialog, BreadCrumbsComponent},
    methods : {
        filter()
        {
            if(this.hint == 'Canaux Active')
            {
                this.init()
            }else if(this.hint == 'Tous les canaux')
            {
                this.callApi(0)
            }else if (this.hint == 'Canaux supprimer')
            {
                this.callApi(1)
            }
        },
        callApi(filter)
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/channel/filter/${filter}?page=${this.page}`).then(e=>{
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
            if(this.hint == 'Canaux Active')
            {
                this.init()
            }else{
                this.filter()
            }
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/channel?page=${this.page}`).then(e=>{
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
            this.channel_id = id
            this.dialog1 = true
        },
        restore(id)
        {
            this.channel_id = id
            this.dialog2 = true
        },
        close()
        {
            this.channel_id = null
            this.dialog1 = false
        },
        close1()
        {
            this.channel_id = null
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
