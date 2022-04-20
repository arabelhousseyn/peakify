<template>
    <div class="bread-crumbs">
        <v-sheet elevation="0">
                <v-breadcrumbs :items="items">
                    <template v-slot:divider>
                        <v-icon>mdi-chevron-right</v-icon>
                    </template>
                </v-breadcrumbs>
        </v-sheet>
    </div>
</template>

<script>
export default {
    props : ['items']
}
</script>
