<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
    <NcAppContentList>
        <ul>
            <div class="listHeader">
                <NcTextField
                    :value="objectStore.searchTerm"
                    :show-trailing-button="objectStore.searchTerm !== ''"
                    label="Search"
                    class="searchField"
                    trailing-button-icon="close"
                    @input="objectStore.setSearchTerm($event.target.value)"
                    @trailing-button-click="objectStore.clearSearch()">
                    <Magnify :size="20" />
                </NcTextField>
                <NcActions>
                    <NcActionButton @click="objectStore.refreshObjectList()">
                        <template #icon>
                            <Refresh :size="20" />
                        </template>
                        Ververs
                    </NcActionButton>
                    <NcActionButton @click="objectStore.setObjectItem(null); navigationStore.setModal('editAbility')">
                        <template #icon>
                            <Plus :size="20" />
                        </template>
                        Vaardigheid toevoegen
                    </NcActionButton>
                </NcActions>
            </div>

            <div v-if="objectStore.objectList && objectStore.objectList.length > 0 && !objectStore.isLoadingObjectList">
                <NcListItem v-for="(ability, i) in objectStore.objectList"
                    :key="`${ability}${i}`"
                    :name="ability?.name"
                    :force-display-actions="true"
                    :active="objectStore.objectItem?.id === ability?.id"
                    @click="objectStore.setObjectItem(ability)">
                    <template #icon>
                        <AccountGroup :class="objectStore.objectItem?.id === ability?.id && 'selectedIcon'"
                            disable-menu
                            :size="44" />
                    </template>
                    <template #subname>
                        {{ ability?.description || 'Geen beschrijving' }}
                    </template>
                    <template #actions>
                        <NcActionButton @click="objectStore.setObjectItem(ability); navigationStore.setModal('editAbility')">
                            <template #icon>
                                <Pencil />
                            </template>
                            Bewerken
                        </NcActionButton>
                        <NcActionButton @click="objectStore.setObjectItem(ability); navigationStore.setDialog('deleteAbility')">
                            <template #icon>
                                <TrashCanOutline />
                            </template>
                            Verwijderen
                        </NcActionButton>
                    </template>
                </NcListItem>
            </div>
        </ul>

        <NcLoadingIcon v-if="objectStore.isLoadingObjectList"
            class="loadingIcon"
            :size="64"
            appearance="dark"
            name="Vaardigheden aan het laden" />

        <div v-if="objectStore.objectList.length === 0 && !objectStore.isLoadingObjectList">
            Er zijn nog geen vaardigheden gedefinieerd.
        </div>
    </NcAppContentList>
</template>

<script>
import { NcListItem, NcActions, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'
import Magnify from 'vue-material-design-icons/Magnify.vue'
import Refresh from 'vue-material-design-icons/Refresh.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'
import AccountGroup from 'vue-material-design-icons/AccountGroup.vue'

export default {
    name: 'AbilitiesList',
    components: {
        NcListItem,
        NcActions,
        NcActionButton,
        NcAppContentList,
        NcTextField,
        NcLoadingIcon,
        Magnify,
        Refresh,
        Plus,
        Pencil,
        TrashCanOutline,
        AccountGroup,
    },
    mounted() {
        objectStore.refreshObjectList()
    },
}
</script>

<style>
.listHeader {
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: var(--color-main-background);
    border-bottom: 1px solid var(--color-border);
}

.searchField {
    padding-inline-start: 65px;
    padding-inline-end: 20px;
    margin-block-end: 6px;
}

.selectedIcon>svg {
    fill: var(--color-primary);
}

.loadingIcon {
    margin-block-start: var(--OC-margin-20);
}
</style>
