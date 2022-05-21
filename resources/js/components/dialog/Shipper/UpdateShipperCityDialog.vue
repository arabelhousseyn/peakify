<template>
    <div class="delete-shipper-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="500"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Voulez-vous Modifier ?
                </v-card-title>
                <v-card-text>
                    <form method="put" @submit.prevent="update">
                        <v-row>
                            <v-col cols="12" md="6" sm="6">
                                <v-text-field
                                    :label="price.toString()"
                                    :placeholder="price.toString()"
                                    v-model="data1.price"
                                    solo
                                ></v-text-field>
                            </v-col>
                            <v-col v-if="!is_default" cols="12" md="6" sm="6">
                                <v-checkbox
                                    label="Par défaut"
                                    v-model="def"
                                    color="green"
                                ></v-checkbox>
                            </v-col>
                            <v-col v-else cols="12" md="6" sm="6">
                                <v-chip color="green" dark>Par défaut</v-chip>
                            </v-col>

                            <v-col cols="12">
                                <v-btn text type="submit" color="success">
                                    <span v-if="!loading2"><v-icon>mdi-account-edit</v-icon> Modifier</span>
                                    <v-progress-circular
                                        v-else
                                        indeterminate
                                    ></v-progress-circular>
                                </v-btn>
                            </v-col>
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
    props : ['dialog','price','shipper_city_id','city_id','is_default'],
    data : ()=>({
        loading : false,
        data1 : {
            price : null,
            city_id : null,
            type : 'S'
        },
        def : false,
        loading2 : false,
    }),
    methods : {
        update()
        {
            this.loading2 = true
            this.data1.city_id = this.city_id

            this.data1.type = (this.def) ? 'D' : 'S'
            this.data1.price = (this.data1.price == null) ? this.price : (this.data1.price)

            this.loading = true
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/shipper/cities/update-shipper-city/${this.shipper_city_id}`,this.data1).then(e => {
                    if(e.status == 204)
                    {
                        this.$toast.open({
                            message : "Opération effectué",
                            type : 'success'
                        })
                         this.$emit('close',true)
                    }
                })
            })
        },
        close()
        {
            this.$emit('close')
        }
    }
}
</script>
