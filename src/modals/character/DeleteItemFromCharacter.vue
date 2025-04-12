<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcDialog v-if="navigationStore.dialog === 'deleteItemFromCharacter'"
		name="Item verwijderen"
		size="normal"
		:can-close="false">
		<p v-if="!success">
			Wil je <b>{{ objectStore.objectItem.name }}</b> definitief verwijderen? Deze actie kan niet ongedaan worden gemaakt.
		</p>

		<NcNoteCard v-if="success" type="success">
			<p>Item succesvol verwijderd</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<template #actions>
			<NcButton
				@click="navigationStore.setDialog(false)">
				<template #icon>
					<Cancel :size="20" />
				</template>
				{{ success ? 'Sluiten' : 'Annuleer' }}
			</NcButton>
			<NcButton
				v-if="!success"
				:disabled="loading"
				type="error"
				@click="deleteItemFromCharacter()">
				<template #icon>
					<NcLoadingIcon v-if="loading" :size="20" />
					<TrashCanOutline v-if="!loading" :size="20" />
				</template>
				Verwijderen
			</NcButton>
		</template>
	</NcDialog>
</template>

<script>
import {
	NcButton,
	NcDialog,
	NcLoadingIcon,
	NcNoteCard,
} from '@nextcloud/vue'

import Cancel from 'vue-material-design-icons/Cancel.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'DeleteItemFromCharacter',
	components: {
		NcDialog,
		NcButton,
		NcLoadingIcon,
		NcNoteCard,
		// Icons
		TrashCanOutline,
		Cancel,
	},
	data() {
		return {
			success: false,
			loading: false,
			error: false,
		}
	},
	methods: {
		async deleteItemFromCharacter() {
			this.loading = true
			try {
				const characterItemClone = { ...objectStore.objectItem }

				// Find the index of the item to delete
				const index = characterItemClone.items.findIndex(item => item === objectStore.objectItem.id)

				// Remove the item if it exists
				if (index !== -1) {
					characterItemClone.items.splice(index, 1)
				} else {
					throw Error('Item could not be found on character, this may be because it is already deleted.')
				}

				if (!characterItemClone.id) {
					throw Error('Error: character ID was not found')
				}

				// Set the object type to 'character' before saving
				objectStore.setObjectType('character')
				await objectStore.saveObject(characterItemClone)

				// Close modal or show success message
				this.success = true
				this.loading = false
				this.error = false
				setTimeout(() => {
					this.success = false
					navigationStore.setDialog(false)
				}, 2000)
			} catch (error) {
				this.loading = false
				this.success = false
				this.error = error.message || 'An error occurred while deleting the item'
			}
		},
	},
}
</script>

<style lang="css">
.notecard h2 {
    margin: 0;
}
</style>
