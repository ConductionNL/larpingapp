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
					<NcActionButton @click="objectStore.setObjectItem(null); navigationStore.setModal('editEffect')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Effect toevoegen
					</NcActionButton>
				</NcActions>
			</div>
			<div v-if="objectStore.objectList && objectStore.objectList.length > 0 && !objectStore.isLoadingObjectList">
				<NcListItem v-for="(effect, i) in objectStore.objectList"
					:key="`${effect}${i}`"
					:name="effect.name"
					:active="objectStore.objectItem?.id === effect?.id"
					:force-display-actions="true"
					:details="effect?.modification || ''"
					:counter-number="effect?.modifier"
					@click="objectStore.setObjectItem(effect)">
					<template #icon>
						<MagicStaff :class="objectStore.objectItem === effect.id && 'selectedZaakIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ effect?.name }}
					</template>
					<template #actions>
						<NcActionButton @click="objectStore.setObjectItem(effect); navigationStore.setModal('editEffect')">
							<template #icon>
								<Pencil />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="objectStore.setObjectItem(effect); navigationStore.setDialog('deleteEffect')">
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
			name="Effecten aan het laden" />

		<div v-if="objectStore.objectList.length === 0 && !objectStore.isLoadingObjectList">
			Er zijn nog geen effecten gedefinieerd.
		</div>
	</NcAppContentList>
</template>
<script>
// Components
import { NcListItem, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon, NcActions } from '@nextcloud/vue'

// Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import MagicStaff from 'vue-material-design-icons/MagicStaff.vue'
import Refresh from 'vue-material-design-icons/Refresh.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'EffectsList',
	components: {
		// Components
		NcListItem,
		NcActions,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		MagicStaff,
		Magnify,
		Pencil,
		TrashCanOutline,
		Plus,
		Refresh,
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
	fill: white;
}

.loadingIcon {
	margin-block-start: var(--OC-margin-20);
}
</style>
