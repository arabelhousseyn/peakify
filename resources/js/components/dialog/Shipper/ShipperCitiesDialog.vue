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
                                    Etat
                                </th>
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
                                <td>
                                    <v-chip color="green" v-if="default_city == item.city._id" dark>Par défaut</v-chip>
                                    <v-chip color="red" v-else dark>Secondaire</v-chip>
                                </td>
                                <td>{{ item.city.name }}</td>
                                <td>
                                    <span>{{ item.price }}</span>
                                </td>
                                <td>
                                    <v-btn color="green" @click="update(item._id,item.city._id,item.priceValue,default_city == item.city._id)" rounded small dark elevation="1"><v-icon>mdi-pen</v-icon></v-btn>
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
        <update-shipper-city-dialog v-if="dialog2" @close="close3" :dialog="dialog2" :shipper_city_id="shipper_city_id"
        :city_id="city_id" :price="price" :is_default="is_default" />
    </div>
</template>

<script>

import DestroyShipperCityDialog from "./DestroyShipperCityDialog";
import UpdateShipperCityDialog from "./UpdateShipperCityDialog";
export default {
    components: {UpdateShipperCityDialog, DestroyShipperCityDialog},
    props : ['shipper_id','dialog'],
    data : () =>({
        data : [],
        loading : true,
        dialog1 : false,
        dialog2 : false,
        shipper_city_id : null,
        city_id : null,
        price : null,
        default_city : null,
        is_default : false,
    }),
    methods : {
        colse()
        {
            this.$emit('close')
        },
        update(shipper_city_id,city_id,price,is_default)
        {
            this.shipper_city_id = shipper_city_id
            this.city_id = city_id
            this.price = price
            this.is_default = is_default
            this.dialog2 = true
        },
        destroy(shipper_city_id)
        {
            this.shipper_city_id = shipper_city_id
            this.dialog1 = true
        },
        init()
        {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.get(`/api/shipper/cities/${this.shipper_id}`).then(e => {
                    this.data = e.data.data
                    this.default_city = e.data.default_city
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
        },
        close3(init = false)
        {
            if(init)
            {
                this.init()
            }
            this.shipper_city_id = null
            this.city_id = null
            this.price = null
            this.is_default = false
            this.dialog2 = false
        }
    },
    mounted() {
        this.init()
    }
}
</script>
