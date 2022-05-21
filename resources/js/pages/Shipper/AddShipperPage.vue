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

                            <v-col cols="12">
                                <v-expansion-panels flat>
                                    <v-expansion-panel elevation="1">
                                        <v-expansion-panel-header disable-icon-rotate>
                                            <strong>Villes</strong>
                                            <template v-slot:actions>
                                                <p>
                                                    <small>Ajouter les villes</small>
                                                    <v-icon color="primary">
                                                        mdi-plus
                                                    </v-icon>
                                                </p>
                                            </template>
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-row v-for="(input,index) in inputs" :key="index">
                                                <v-col cols="12" lg="6" md="6">
                                                    <v-combobox
                                                        @change="mutateValue($event,'C',index)"
                                                        :items="cities"
                                                        solo
                                                        label="Ville"
                                                        prepend-inner-icon="mdi-city"
                                                    ></v-combobox>
                                                </v-col>

                                                <v-col cols="12" lg="6" md="6">
                                                    <v-text-field
                                                        @change="mutateValue($event,'P',index)"
                                                        solo
                                                        label="Prix"
                                                        prepend-inner-icon="mdi-currency-usd"
                                                    ></v-text-field>
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
        inputs : 1,
        errors : [],
        cities : [],
        fruits : [],
        types : ['Personne','Société'],
        city_id : null,
        price : null

    }),
    components: {BreadCrumbsComponent},
    methods : {
        incrementInputs()
        {
            this.resetParamsShipper()
            this.inputs++
        },
        decrementInput()
        {
            if(this.inputs > 1)
            {
                this.data.cities.pop()
                this.inputs--
            }
        },
        store()
        {
            this.loading = true
            this.disabled = true

            this.data.type = (this.data.type == 'Personne') ? 'P' : 'C'

            console.log(this.data)

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
        mutateValue(value,attribute,index)
        {
            switch (attribute)
            {
                case 'C' :
                    let city = this.fruits.filter(function (fruit){
                        return fruit.name == this
                    },value)[0]
                    this.city_id = city._id
                    break;
                case 'P' : this.price = value; break;
            }

            if(this.data.cities[index] !== undefined)
            {
                this.data.cities[index].city_id = this.city_id
                this.data.cities[index].price = this.price
            }else{
                if(this.city_id !== null && this.price !== null)
                {
                    let data = {
                        city_id : this.city_id,
                        price : this.price,
                    }
                    this.data.cities.push(data)
                }
            }
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
            this.inputs = 0
            setTimeout(()=>{
                this.inputs = 1
            },3000)
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
        },
        resetParamsShipper()
        {
            this.city_id = null
            this.price = null
        }
    },
    mounted() {
        this.init()
    }
}
</script>
