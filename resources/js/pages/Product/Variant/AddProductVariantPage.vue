<template>
    <div class="add-offers-page">
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
                                            <strong>Variantes</strong>
                                            <template v-slot:actions>
                                                <p>
                                                    <small>Ajouter les variantes</small>
                                                    <v-icon color="primary">
                                                        mdi-plus
                                                    </v-icon>
                                                </p>
                                            </template>
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-row v-for="(input,index) in inputs1" :key="index">

                                                <v-col cols="12" lg="6" md="6">
                                                    <v-text-field
                                                        @change="mutateValue1($event,'C',index)"
                                                        solo
                                                        label="Code variante*"
                                                        prepend-inner-icon="mdi-square"
                                                    ></v-text-field>
                                                </v-col>

                                                <v-col cols="12" lg="6" md="6">
                                                    <v-text-field
                                                        @change="mutateValue1($event,'P',index)"
                                                        solo
                                                        label="Prix*"
                                                        prepend-inner-icon="mdi-currency-usd"
                                                    ></v-text-field>
                                                </v-col>

                                                <v-col cols="12">
                                                    <v-expansion-panels :disabled="disabled_panel" flat>
                                                        <v-expansion-panel elevation="1">
                                                            <v-expansion-panel-header disable-icon-rotate>
                                                                <strong>Options</strong>
                                                                <template v-slot:actions>
                                                                    <p>
                                                                        <small>Ajouter les options</small>
                                                                        <v-icon color="primary">
                                                                            mdi-plus
                                                                        </v-icon>
                                                                    </p>
                                                                </template>
                                                            </v-expansion-panel-header>
                                                            <v-expansion-panel-content>
                                                                <v-row v-for="(inp,ind) in input.nbr" :key="ind">

                                                                    <v-col cols="12" lg="6" md="6">
                                                                        <v-combobox
                                                                            @change="fetchValues"
                                                                            :items="options"
                                                                            label="Options*"
                                                                            dense
                                                                            solo
                                                                        ></v-combobox>
                                                                    </v-col>

                                                                    <v-col cols="12" lg="6" md="6">
                                                                        <v-combobox
                                                                            @change="mutateValue2($event,index,ind)"
                                                                            :items="values"
                                                                            label="Valeur*"
                                                                            dense
                                                                            solo
                                                                        ></v-combobox>
                                                                    </v-col>
                                                                </v-row>
                                                                <v-btn color="success" @click="incrementInputs2(index)" rounded text><v-icon>mdi-plus</v-icon></v-btn>
                                                                <v-btn color="error" @click="decrementInput2(index)" rounded text><v-icon>mdi-minus</v-icon></v-btn>
                                                            </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>
                                                </v-col>
                                            </v-row>
                                            <v-btn color="success" @click="incrementInputs1" rounded text><v-icon>mdi-plus</v-icon></v-btn>
                                            <v-btn color="error" @click="decrementInput1" rounded text><v-icon>mdi-minus</v-icon></v-btn>
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
            variants : []
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
        inputs1 : [{nbr : 1}],
        code : null,
        price : null,
        options : [],
        values : [],
        disabled_panel : true,
    }),
    components: {BreadCrumbsComponent},
    methods : {
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get('/api/option/all').then(e=>{
                    this.fruits1 = e.data.data
                    this.options = this.fruits1.map(function(fruit){
                        return fruit.name
                    })
                })
            })
        },
        store()
        {

            this.loading = true
            this.disabled = true

            let url = window.location.href.split('/')

            this.data.product_id = url[url.length - 2]

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/product/variants/store',this.data).then(e=>{
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
            this.data.variants = []
            this.code = null
            this.price = null
            this.inputs1 = 0
            setTimeout(()=>{
                this.inputs1 = [{nbr : 1}]
            },2000)
            this.loading = false
            this.disabled = true
        },
        mutateValue1(value,attribute,index)
        {
            switch (attribute)
            {
                case 'C' : this.code = value; break;
                case 'P' : this.price = value; break;
            }

            if(this.data.variants[index] !== undefined)
            {
                this.data.variants[index].code = this.code
                this.data.variants[index].price = this.price
            }else{
                if(this.code !== null && this.price !== null)
                {
                    let data = {
                        code : this.code,
                        price : this.price,
                        options : []
                    }
                    this.disabled_panel = false
                    this.data.variants.push(data)
                }else{
                    this.disabled_panel = true
                }
            }
        },
        incrementInputs1()
        {
            this.resetParamsVariants()
            this.inputs1.push({
                nbr : 1
            })
        },
        decrementInput1()
        {
            if(this.inputs1.length > 1)
            {
                this.data.variants.pop()
                this.inputs1.pop()
            }
        },
        incrementInputs2(index)
        {
            this.inputs1[index].nbr++
        },
        decrementInput2(index)
        {
            if(this.inputs1[index].nbr > 1)
            {
                this.data.variants[index].options.pop()
                this.inputs1[index].nbr--
            }
        },
        fetchValues(option_name)
        {
            let filter = this.fruits1.filter(function (fruit){
                return fruit.name == this
            },option_name)[0]

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/option/values/${filter._id}`).then(e=>{
                    this.fruits2 = e.data.data
                    this.values = this.fruits2.map(function(fruit){
                        return fruit.value
                    })
                })
            })
        },
        mutateValue2(value,index_variant,index_value)
        {
            this.disabled = false
            let filter = this.fruits2.filter(function (fruit){
                return fruit.value == this
            },value)[0]


            if(this.data.variants[index_variant].options[index_value] == undefined)
            {
                this.data.variants[index_variant].options.push({option_value_id : filter._id})
            }else{
                this.data.variants[index_variant].options[index_value].option_value_id = filter._id
            }
        },
        resetParamsOffers()
        {
            this.discount = null
            this.quantity = null
            this.is_static = false
        },
        resetParamsVariants()
        {
            this.price = null
            this.code = null
        },
    },
    mounted() {
        this.init()
    }
}
</script>
