<template>
    <div class="update-category-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-btn class="mt-3" @click="$router.push('/home/shippers')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>
            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Modifier une catégories</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="handle">
                        <v-row>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    v-model="infos.full_name"
                                    solo
                                    required
                                    label="Nom complet*"
                                    prepend-inner-icon="mdi-square"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    v-model="infos.phone"
                                    solo
                                    required
                                    label="Telephone*"
                                    prepend-inner-icon="mdi-phone"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    v-model="infos.email"
                                    type="email"
                                    solo
                                    required
                                    label="Email*"
                                    prepend-inner-icon="mdi-email"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-select
                                    v-model="infos.type"
                                    solo
                                    :items="types"
                                    required
                                    label="Type*"
                                ></v-select>
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
        shipper_id : window.location.href.split('/').pop(),
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
                text: 'Modifier un livreur',
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
        types : ['C','P']
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

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/shipper/${this.shipper_id}`,this.infos).then(e=>{
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
                axios.get(`/api/shipper/shipper-details/${this.shipper_id}`).then(e=>{
                    this.infos = e.data.data
                    this.disable = false
                }).catch(err => {
                    window.location.href = '/home/categories'
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
