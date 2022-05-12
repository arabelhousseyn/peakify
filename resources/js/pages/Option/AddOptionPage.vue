<template>
    <div class="add-option-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />

            <v-btn class="mt-3" @click="$router.push('/home/options')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>

            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Ajouter une option</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="store">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    @keydown="check"
                                    v-model="data.name"
                                    solo
                                    required
                                    label="Nom option*"
                                    prepend-inner-icon="mdi-square"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-expansion-panels flat>
                                    <v-expansion-panel elevation="1">
                                        <v-expansion-panel-header disable-icon-rotate>
                                            <strong>Valeurs</strong>
                                            <template v-slot:actions>
                                                <p>
                                                    <small>Ajouter les valeurs</small>
                                                    <v-icon color="primary">
                                                        mdi-plus
                                                    </v-icon>
                                                </p>
                                            </template>
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                                        <v-text-field
                                                            v-for="(input,index) in inputs" :key="index"
                                                            @change="mutateValue($event,index)"
                                                            solo
                                                            required
                                                            label="valeur"
                                                            prepend-inner-icon="mdi-square"
                                                        ></v-text-field>
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
            name : null,
            values : []
        },
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
                text: 'Ajouter une option',
                disabled: false,
                href: '/home/options/add-option',
            },
        ],
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],
        inputs : 1,
    }),
    components: {BreadCrumbsComponent},
    methods : {
        store()
        {
            this.loading = true
            this.disabled = true
            console.log(this.data.values)
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/option',this.data).then(e=>{
                    this.$toast.open({
                        message : "Opération effectué",
                        type : 'success'
                    })
                    this.data.values = []
                    this.inputs = 0
                    setTimeout(()=>{
                        this.inputs = 1
                    },2000)
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
        check()
        {
            if(this.hasError)
            {
                this.hasError = false
                this.errors = []
            }
            this.disabled = (this.data.name == null) ? true : false
        },
        incrementInputs()
        {
          this.inputs++
        },
        mutateValue(value,index)
        {
            if(this.data.values[index] !== undefined)
            {
                this.data.values[index] = {value : value}
            }else{
                this.data.values.push({value : value})
            }
        },
        decrementInput()
        {
            if(this.inputs > 1)
            {
                this.data.values.pop()
                this.inputs--
            }
        },
        empty()
        {
            this.data.name = null
        }
    }
}
</script>
