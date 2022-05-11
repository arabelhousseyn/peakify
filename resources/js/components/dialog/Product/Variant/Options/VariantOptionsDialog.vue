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
                                    <v-btn color="red" @click="destroy(item._id)" rounded small dark elevation="1"><v-icon>mdi-trash-can</v-icon></v-btn>
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
        <delete-variant-option-dialog @close="close1" @close1="close2" :dialog="dialog1" :product_variant_option_id="product_variant_option_id" />
    </div>
</template>

<script>
import DeleteVariantOptionDialog from "./DeleteVariantOptionDialog";
export default {
    components: {DeleteVariantOptionDialog},
    props : ['product_variant_id','dialog'],
    data : () =>({
        data : [],
        loading : true,
        dialog1 : false,
        product_variant_option_id : null,
    }),
    methods : {
        colse()
        {
            this.$emit('close')
        },
        destroy(product_variant_option_id)
        {
            this.dialog1 = true
            this.product_variant_option_id = product_variant_option_id
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
        },
        close1(product_variant_option_id)
        {
            this.dialog1 = false
            this.product_variant_option_id = null
        },
        close2(product_variant_option_id)
        {
            for (let i = 0;i<this.data.length;i++)
            {
                if(this.data[i]._id == product_variant_option_id)
                {
                    this.data.splice(i,1)
                }
            }

            this.dialog1 = false
            this.product_variant_option_id = null
        }
    },
    mounted() {
        this.init()
    }
}
</script>
