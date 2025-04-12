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
					<NcActionButton @click="objectStore.setObjectItem(null); navigationStore.setModal('editItem')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Item toevoegen
					</NcActionButton>
				</NcActions>
			</div>

			<div v-if="objectStore.objectList && objectStore.objectList.length > 0 && !objectStore.isLoadingObjectList">
				<NcListItem v-for="(item, i) in objectStore.objectList"
					:key="`${item}${i}`"
					:name="item?.name"
					:active="objectStore.objectItem?.id === item?.id"
					:details="item.unique ? 'unique' : 'non-unique'"
					:force-display-actions="true"
					@click="objectStore.setObjectItem(item)">
					<template #icon>
						<Sword :class="objectStore.objectItem?.id === item?.id && 'selectedItemIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ item?.description }}
					</template>
					<template #actions>
						<NcActionButton @click="objectStore.setObjectItem(item); navigationStore.setModal('editItem')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="objectStore.setObjectItem(item); navigationStore.setDialog('deleteItem')">
							<template #icon>
								<TrashCanOutline :size="20" />
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
			name="Items aan het laden" />

		<div v-if="objectStore.objectList.length === 0 && !objectStore.isLoadingObjectList">
			Er zijn nog geen items gedefinieerd.
		</div>
	</NcAppContentList>
</template>
<script>
//  Components
import { NcListItem, NcActions, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'

// Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import Sword from 'vue-material-design-icons/Sword.vue'
import Refresh from 'vue-material-design-icons/Refresh.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'ItemsList',
	components: {
		// Components
		NcListItem,
		NcActions,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		Magnify,
		Refresh,
		Plus,
		Sword,
		Pencil,
		TrashCanOutline,
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
