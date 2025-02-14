<script setup>
import { itemStore, navigationStore, searchStore } from '../../store/store.js'
</script>

<template>
	<NcAppContentList>
		<ul>
			<div class="listHeader">
				<NcTextField
					:value="itemStore.searchTerm"
					:show-trailing-button="itemStore.searchTerm !== ''"
					label="Search"
					class="searchField"
					trailing-button-icon="close"
					@input="itemStore.setSearchTerm($event.target.value)"
					@trailing-button-click="itemStore.clearSearch()">
					<Magnify :size="20" />
				</NcTextField>
				<NcActions>
					<NcActionButton @click="itemStore.refreshItemList()">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="itemStore.setItemItem(null); navigationStore.setModal('editItem')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Item toevoegen
					</NcActionButton>
				</NcActions>
			</div>

			<div v-if="itemStore.itemList && itemStore.itemList.length > 0 && !itemStore.isLoadingItemList">
				<NcListItem v-for="(item, i) in itemStore.itemList"
					:key="`${item}${i}`"
					:name="item?.name"
					:active="itemStore.itemItem?.id === item?.id"
					:details="item.unique ? 'unique' : 'non-unique'"
					:force-display-actions="true"
					@click="handleItemSelect(item)">
					<template #icon>
						<Sword :class="itemStore.itemItem?.id === item?.id && 'selectedItemIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ item?.description }}
					</template>
					<template #actions>
						<NcActionButton @click="itemStore.setItemItem(item); navigationStore.setModal('editItem')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="itemStore.setItemItem(item); navigationStore.setDialog('deleteItem')">
							<template #icon>
								<TrashCanOutline :size="20" />
							</template>
							Verwijderen
						</NcActionButton>
					</template>
				</NcListItem>
			</div>
		</ul>

		<NcLoadingIcon v-if="itemStore.isLoadingItemList"
			class="loadingIcon"
			:size="64"
			appearance="dark"
			name="Items aan het laden" />

		<div v-if="itemStore.itemList.length === 0 && !itemStore.isLoadingItemList">
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
		itemStore.refreshItemList()
	},
	methods: {
		/**
		 * Handle item selection and fetch related data
		 * @param {Object} item - The selected item object
		 */
		async handleItemSelect(item) {
			// Set the selected item in the store
			itemStore.setItemItem(item)
		},
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
