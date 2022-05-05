<template>
    <div class="update-option-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-btn class="mt-3" @click="$router.push('/home/options')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>
            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Modifier une option</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="update">
                        <v-row>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    :disabled="disable"
                                    v-model="infos.name"
                                    solo
                                    required
                                    label="Nom option*"
                                    prepend-inner-icon="mdi-square"
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
    </div>
</template>
<script>
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent";
export default {
    props : ['data'],
    data : ()=>({
        option_id : window.location.href.split('/').pop(),
        items : [
            {
                text: 'Tableau de bord',
                disabled: false,
                href: '/',
            },
            {
                text: 'options',
                disabled: false,
                href: '/home/options',
            },
            {
                text: 'Modifier une option',
                disabled: true,
                href: '',
            },
        ],
        disabled : false,
        loading : false,
        hasError : false,
        errors : [],
        disable : false,
        infos : {}
    }),
    components: {BreadCrumbsComponent},
    methods : {
        update()
        {
            this.loading = true
            this.disabled = true

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/option/${this.option_id}`,this.infos).then(e=>{
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
                axios.get(`/api/option/option-details/${this.option_id}`).then(e=>{
                    this.infos = e.data.data
                    this.disable = false
                }).catch(err => {
                    window.location.href = '/home/options'
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
