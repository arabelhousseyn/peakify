<template>
    <div class="access-hours-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="650"
        >
            <v-card>
                <v-card-title class="text-h5">
                    définir les heures de travail
                </v-card-title>

                <v-card-text>
                    <v-row>
                        <v-col cols="12" lg="6" sm="6">
                            <h2>Commencer à : </h2>
                            <v-time-picker
                                v-model="data.start_at"
                            ></v-time-picker>
                        </v-col>
                        <v-col cols="12" lg="6" sm="6">
                            <h2>Fini à : </h2>
                            <v-time-picker
                                v-model="data.end_at"
                            ></v-time-picker>
                        </v-col>
                    </v-row>
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
                    <v-btn
                        color="green"
                        text
                        @click="handle"
                    >
                        <span v-if="!loading">Confirmer</span>
                        <v-progress-circular
                            v-else
                            indeterminate
                        ></v-progress-circular>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props : ['dialog','user_id'],
    data : ()=>({
        data : {
            start_at : null,
            end_at : null,
        },
        loading : false,
    }),
    methods : {
        handle()
        {
            this.loading = true
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/user/define-hours/${this.user_id}`,this.data).then(e => {
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
        close()
        {
            this.$emit('close')
        }
    }
}
</script>
