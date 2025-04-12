<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcDialog v-if="navigationStore.modal === 'addItemToCharacter'"
		name="Items toevoegen"
		size="normal"
		:can-close="false"
		@close="closeModal">
		<NcNoteCard v-if="success" type="success">
			<p>Items succesvol toegevoegd</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<div v-if="!success" class="formContainer">
			<NcSelect v-bind="items"
				v-model="selectedItems"
				input-label="Items *"
				:loading="itemsLoading"
				:disabled="itemsLoading || loading" />
		</div>

		<template #actions>
			<NcButton
				@click="closeModal">
				<template #icon>
					<Cancel :size="20" />
				</template>
				{{ success ? 'Sluiten' : 'Annuleer' }}
			</NcButton>
			<NcButton
				@click="openLink('https://conduction.gitbook.io/opencatalogi-nextcloud/gebruikers/publicaties', '_blank')">
				<template #icon>
					<Help :size="20" />
				</template>
				Help
			</NcButton>
			<NcButton
				v-if="!success"
				:disabled="loading"
				type="primary"
				@click="addItemsToCharacter()">
				<template #icon>
					<NcLoadingIcon v-if="loading" :size="20" />
					<Save v-if="!loading" :size="20" />
				</template>
				Opslaan
			</NcButton>
		</template>
	</NcDialog>
</template>

<script>
import {
	NcButton,
	NcDialog,
	NcSelect,
	NcLoadingIcon,
	NcNoteCard,
} from '@nextcloud/vue'

import Cancel from 'vue-material-design-icons/Cancel.vue'
import Save from 'vue-material-design-icons/ContentSaveOutline.vue'
import Help from 'vue-material-design-icons/Help.vue'

export default {
	name: 'AddItemToCharacter',
	components: {
		NcDialog,
		NcButton,
		NcSelect,
		NcLoadingIcon,
		NcNoteCard,
		// Icons
		Cancel,
		Save,
		Help,
	},
	data() {
		return {
			items: {},
			selectedItems: [],
			itemsLoading: false,
			success: false,
			loading: false,
			error: false,
			hasUpdated: false,
		}
	},
	mounted() {
		this.fetchItems()
	},
	updated() {
		if (navigationStore.modal === 'addItemToCharacter' && !this.hasUpdated) {
			this.fetchItems()
			this.hasUpdated = true
		}
	},
	methods: {
		closeModal() {
			navigationStore.setModal(false)
			this.success = false
			this.loading = false
			this.error = false
			this.hasUpdated = false
			this.selectedItems = []
		},
		async fetchItems() {
			this.itemsLoading = true

			// Store current object type
			const currentType = objectStore.objectType
			
			// Switch to item type to fetch items
			objectStore.setObjectType('item')
			await objectStore.refreshObjectList()
				.then(() => {
					// Create options from all available items
					this.items = {
						multiple: true,
						closeOnSelect: false,
						options: objectStore.objectList.map((item) => ({
							id: item.id,
							label: item.name,
						})),
					}

					// Pre-select existing items
					if (objectStore.objectItem?.items?.length) {
						this.selectedItems = objectStore.objectItem.items.map(item => ({
							id: item.id || item,
							label: objectStore.objectList.find(i => i.id === (item.id || item))?.name || '',
						}))
					}

					this.itemsLoading = false
					
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
				.catch((error) => {
					console.error('Error fetching items:', error)
					this.itemsLoading = false
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
		},
		async addItemsToCharacter() {
			this.loading = true
			try {
				const characterItemClone = { ...objectStore.objectItem }
				
				// Replace items array with selected items, ensuring uniqueness
				const uniqueItems = [...new Map(this.selectedItems.map(item => [item.id, item])).values()]
				
				// Store current object type
				const currentType = objectStore.objectType
				
				// Switch to item type to fetch full item data
				objectStore.setObjectType('item')
				await objectStore.refreshObjectList()
				
				characterItemClone.items = uniqueItems.map(selected => {
					const itemData = objectStore.objectList.find(i => i.id === selected.id)
					return {
						objectType: 'item',
						id: itemData.id,
						name: itemData.name,
						description: itemData.description || '',
						effects: itemData.effects || [],
					}
				})

				// Switch back to character type for saving
				objectStore.setObjectType('character')
				await objectStore.saveObject(characterItemClone)

				this.success = true
				this.loading = false
				this.error = false
				setTimeout(() => {
					this.closeModal()
				}, 2000)
			} catch (error) {
				this.loading = false
				this.success = false
				this.error = error.message || 'Er is een fout opgetreden bij het bewerken van de voorwerpen'
			}
		},
		openLink(url, target) {
			window.open(url, target)
		},
	},
}
</script>
