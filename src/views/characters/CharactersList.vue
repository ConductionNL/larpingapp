<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore, searchStore } from '../../store/store.js'
import { onMounted } from 'vue'

const objectStore = useObjectStore()
const EXTENSION_PARAMS = { _extend: 'ocName,skills,items,conditions,events' }

// Set the object type to 'character' and load the list
onMounted(() => {
	objectStore.setObjectType('character')
	refreshCharacterList()
})

// Function to handle refresh with additional parameters
async function refreshCharacterList() {
	await objectStore.refreshObjectList('character', EXTENSION_PARAMS)
}

// Handle character selection
async function handleCharacterSelect(character) {
	await objectStore.setObjectItem(character)
}
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
					<NcActionButton @click="refreshCharacterList()">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="objectStore.setObjectItem(null); navigationStore.setModal('editCharacter')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Karakter toevoegen
					</NcActionButton>
				</NcActions>
			</div>

			<div v-if="objectStore.objectList && objectStore.objectList.length > 0 && !objectStore.isLoadingObjectList">
				<NcListItem v-for="(character, i) in objectStore.objectList"
					:key="`${character}${i}`"
					:name="character?.name"
					:force-display-actions="true"
					:active="objectStore.objectItem?.id === character?.id"
					:details="character.approved === 'approved' ? 'Approved': 'Not approved'"
					:counter-number="character?.skills?.length || 0"
					@click="handleCharacterSelect(character)">
					<template #icon>
						<BriefcaseAccountOutline :class="objectStore.objectItem?.id === character?.id && 'selectedZaakIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ character?.ocName?.name || 'No player selected' }}
					</template>
					<template #actions>
						<NcActionButton @click="objectStore.setObjectItem(character); navigationStore.setModal('editCharacter')">
							<template #icon>
								<Pencil />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="objectStore.setObjectItem(character); navigationStore.setDialog('deleteCharacter')">
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
			name="Karakters aan het laden" />

		<div v-if="objectStore.objectList.length === 0 && !objectStore.isLoadingObjectList">
			Er zijn nog geen karakters gedefinieerd.
		</div>
	</NcAppContentList>
</template>

<script>
// Components
import { NcListItem, NcActions, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'

// Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import BriefcaseAccountOutline from 'vue-material-design-icons/BriefcaseAccountOutline.vue'
import Refresh from 'vue-material-design-icons/Refresh.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'CharactersList',
	components: {
		// Components
		NcListItem,
		NcActions,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		BriefcaseAccountOutline,
		Magnify,
		Refresh,
		Plus,
		Pencil,
		TrashCanOutline,
	}
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
    fill: white;
}

.loadingIcon {
    margin-block-start: var(--OC-margin-20);
}
</style>
