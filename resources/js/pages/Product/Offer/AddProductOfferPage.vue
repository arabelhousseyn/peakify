<template>
    <div class="add-option-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />

            <v-btn class="mt-3" @click="$router.back()" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>

            <v-card width="650" class="mt-5" elevation="0">
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="store">
                        <v-row>
                            <v-col cols="12">
                                <v-expansion-panels flat>
                                    <v-expansion-panel elevation="1">
                                        <v-expansion-panel-header disable-icon-rotate>
                                            <strong>Offres</strong>
                                            <template v-slot:actions>
                                                <p>
                                                    <small>Ajouter les offres</small>
                                                    <v-icon color="primary">
                                                        mdi-plus
                                                    </v-icon>
                                                </p>
                                            </template>
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-row v-for="(input,index) in inputs" :key="index">
                                                <v-col cols="12" lg="4" md="4">
                                                    <v-text-field
                                                        @change="mutateValue($event,'Q',index)"
                                                        solo
                                                        type="number"
                                                        min="1"
                                                        label="Quantité*"
                                                        prepend-inner-icon="mdi-square"
                                                    ></v-text-field>
                                                </v-col>

                                                <v-col cols="12" lg="4" md="4">
                                                    <v-text-field
                                                        @change="mutateValue($event,'D',index)"
                                                        solo
                                                        type="number"
                                                        min="1"
                                                        max="100"
                                                        label="Réduction*"
                                                        prepend-inner-icon="mdi-percent-outline"
                                                    ></v-text-field>
                                                </v-col>

                                                <v-col cols="12" lg="4" md="4">
                                                    <v-checkbox
                                                        @change="mutateValue($event,'T',index)"
                                                        label="Statique"
                                                    ></v-checkbox>
                                                </v-col>
                                            </v-row>
                                            <v-btn color="success" @click="incrementInputs" rounded text><v-icon>mdi-plus</v-icon></v-btn>
                                            <v-btn color="error" @click="decrementInput" rounded text><v-icon>mdi-minus</v-icon></v-btn>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-expansion-panels>
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
import BreadCrumbsComponent from "../../../components/BreadCrumbsComponent";
export default {
    data : ()=>({
        data : {
            offers : []
        },
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
                text: 'offres',
                disabled: true,
                href: '',
            },
            {
                text: 'Ajouter un offre',
                disabled: true,
                href: '',
            },
        ],
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],
        inputs : 1,
        quantity : null,
        discount : null,
        is_static : false,
    }),
    components: {BreadCrumbsComponent},
    methods : {
        store()
        {

            this.loading = true
            this.disabled = true

            let url = window.location.href.split('/')

            this.data.product_id = url[url.length - 2]

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/product/offers/store',this.data).then(e=>{
                    this.$toast.open({
                        message : "Opération effectué",
                        type : 'success'
                    })
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
        empty()
        {
            this.data.offers = []
            this.inputs = 1
            this.loading = false
            this.disabled = true
        },
        incrementInputs()
        {
            this.resetParamsOffers()
            this.inputs++
        },
        mutateValue(value,attribute,index)
        {
            switch (attribute)
            {
                case 'Q' : this.quantity = value; break;
                case 'D' : this.discount = value; break;
                case 'T' : this.is_static = value; break;
            }

            if(this.data.offers[index] !== undefined)
            {
                this.data.offers[index].quantity = this.quantity
                this.data.offers[index].discount = this.discount
                this.data.offers[index].is_static = this.is_static
            }else{
                if(this.quantity !== null && this.discount !== null)
                {
                    let data = {
                        quantity : this.quantity,
                        discount : this.discount,
                        is_static : this.is_static,
                    }
                    this.disabled = false
                    this.data.offers.push(data)
                }
            }
        },
        decrementInput()
        {
            if(this.inputs > 1)
            {
                this.data.offers.pop()
                this.inputs--
            }
        },
        resetParamsOffers()
        {
            this.discount = null
            this.quantity = null
            this.is_static = false
        },
    }
}
</script>
