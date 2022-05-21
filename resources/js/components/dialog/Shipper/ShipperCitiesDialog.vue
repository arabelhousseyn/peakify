<template>
    <div class="shipper-cities-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Villes
                    <v-spacer></v-spacer>
                    <v-btn color="primary"><v-icon>mdi-plus</v-icon> Ajouter</v-btn>
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
                                    Ville
                                </th>
                                <th class="text-left">
                                    Prix
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
                                <td>{{ item.city.name }}</td>
                                <td>{{ item.price }}</td>
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
        <destroy-shipper-city-dialog @close="close1" @close1="close2" :dialog="dialog1" :shipper_city_id="shipper_city_id" />
    </div>
</template>

<script>

import DestroyShipperCityDialog from "./DestroyShipperCityDialog";
export default {
    components: {DestroyShipperCityDialog},
    props : ['shipper_id','dialog'],
    data : () =>({
        data : [],
        loading : true,
        dialog1 : false,
        shipper_city_id : null,
    }),
    methods : {
        colse()
        {
            this.$emit('close')
        },
        destroy(shipper_city_id)
        {
            this.dialog1 = true
            this.shipper_city_id = shipper_city_id
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/shipper/cities/${this.shipper_id}`).then(e => {
                    this.data = e.data.data
                    this.loading = false
                })
            }).catch(err=>{
                this.close()
            })
        },
        close1()
        {
            this.dialog1 = false
            this.shipper_city_id = null
        },
        close2(shipper_city_id)
        {
            for (let i = 0;i<this.data.length;i++)
            {
                if(this.data[i]._id == shipper_city_id)
                {
                    this.data.splice(i,1)
                }
            }

            this.dialog1 = false
            this.shipper_city_id = null
        }
    },
    mounted() {
        this.init()
    }
}
</script>
