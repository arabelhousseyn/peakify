<template>
    <div class="delete-shipper-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ajouter villes
                </v-card-title>
                <v-card-text>
                    <form method="post" @submit.p.prevent="store">
                        <v-row>
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
                                                        label="Ville*"
                                                        prepend-inner-icon="mdi-city"
                                                    ></v-combobox>
                                                </v-col>

                                                <v-col cols="12" lg="6" md="6">
                                                    <v-text-field
                                                        @change="mutateValue($event,'P',index)"
                                                        solo
                                                        label="Prix*"
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
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="close"
                    >
                        Cancel
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props : ['dialog','shipper_id'],
    data : ()=>({
        loading : false,
        data : {
            shipper_id : null,
            cities : []
        },
        cities : [],
        fruits : [],
        city_id : null,
        price : null,
        hasError : false,
        inputs : 1,
        errors : [],
        disabled : true,
    }),
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
                    this.disabled = false
                }
            }
        },
        store()
        {
            this.loading = true
            this.data.shipper_id = this.shipper_id
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post(`/api/shipper/cities/store`,this.data).then(e => {
                    if(e.status == 201)
                    {
                        this.$toast.open({
                            message : "Opération effectué",
                            type : 'success'
                        })
                        this.empty()
                        this.$emit('close',true)
                    }
                })
            })
        },
        close()
        {
            this.$emit('close')
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
        },
        empty()
        {
            this.city_id = null
            this.price = null
            this.inputs = 0
            setTimeout(()=>{
                this.inputs = 1
            },3000)
            this.disabled = true
            this.loading = false
        }
    },
    mounted() {
        this.init()
    }
}
</script>
