<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcDialog v-if="navigationStore.dialog === 'deleteEventFromCharacter'"
		name="Event verwijderen"
		size="normal"
		:can-close="false">
		<p v-if="!success">
			Wil je <b>{{ objectStore.objectItem.name }}</b> definitief verwijderen? Deze actie kan niet ongedaan worden gemaakt.
		</p>

		<NcNoteCard v-if="success" type="success">
			<p>Event succesvol verwijderd</p>
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
				@click="deleteEventFromCharacter()">
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
	name: 'DeleteEventFromCharacter',
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
		async deleteEventFromCharacter() {
			this.loading = true
			try {
				const characterItemClone = { ...objectStore.objectItem }

				// Find the index of the event to delete
				const index = characterItemClone.events.findIndex(event => event === objectStore.objectItem.id)

				// Remove the event if it exists
				if (index !== -1) {
					characterItemClone.events.splice(index, 1)
				} else {
					throw Error('Event could not be found on character, this may be because it is already deleted.')
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
				this.error = error.message || 'An error occurred while deleting the event'
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
