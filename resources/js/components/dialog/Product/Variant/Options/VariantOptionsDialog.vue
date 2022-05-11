<template>
    <div class="variant-options-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Options
                </v-card-title>
                <v-card-text>
                    <v-container v-if="loading">
                        <v-row
                            align-content="center"
                            justify="center"
                        >
                            <v-col
                                class="text-subtitle-1 text-center"
                                cols="12"
                            >
                                Chargement, veuillez patienter
                            </v-col>
                            <v-col cols="6">
                                <v-progress-linear
                                    color="primary"
                                    indeterminate
                                    rounded
                                    height="6"
                                ></v-progress-linear>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-simple-table v-else dense>
                        <template v-slot:default>
                            <thead>
                            <tr>
                                <th class="text-left">
                                    Option
                                </th>
                                <th class="text-left">
                                    Valeur
                                </th>
                                <th class="text-left">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="(item,index) in data"
                                :key="index"
                            >
                                <td>{{ item.value.option.name }}</td>
                                <td>{{ item.value.value }}</td>
                                <td>
                                    <v-btn color="red" rounded small dark elevation="1"><v-icon>mdi-trash-can</v-icon></v-btn>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="colse"
                    >
                        Fermer
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props : ['product_variant_id','dialog'],
    data : () =>({
        data : [],
        loading : true,
    }),
    methods : {
        colse()
        {
            this.$emit('close')
        },
        destroy()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.delete(`/api/product/variants/options/destroy/${this.product_variant_id}`).then(e => {
                    if(e.status == 204)
                    {
                        this.$toast.open({
                            message : "Opération effectué",
                            type : 'success'
                        })
                        window.location.reload()
                    }
                })
            })
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/product/variants/options/${this.product_variant_id}`).then(e => {
                    this.data = e.data.data
                    this.loading = false
                })
            }).catch(err=>{
                this.close()
            })
        }
    },
    mounted() {
        this.init()
    }
}
</script>
