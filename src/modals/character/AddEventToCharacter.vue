<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcDialog v-if="navigationStore.modal === 'addEventToCharacter'"
		name="Events toevoegen"
		size="normal"
		:can-close="false"
		@close="closeModal">
		<NcNoteCard v-if="success" type="success">
			<p>Events succesvol toegevoegd</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<div v-if="!success" class="formContainer">
			<NcSelect v-bind="events"
				v-model="selectedEvents"
				input-label="Events *"
				:loading="eventsLoading"
				:disabled="eventsLoading || loading" />
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
				@click="addEventsToCharacter()">
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
	name: 'AddEventToCharacter',
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
			events: {},
			selectedEvents: [],
			eventsLoading: false,
			success: false,
			loading: false,
			error: false,
			hasUpdated: false,
		}
	},
	mounted() {
		this.fetchEvents()
	},
	updated() {
		if (navigationStore.modal === 'addEventToCharacter' && !this.hasUpdated) {
			this.fetchEvents()
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
			this.selectedEvents = []
		},
		async fetchEvents() {
			this.eventsLoading = true

			// Store current object type
			const currentType = objectStore.objectType
			
			// Switch to event type to fetch events
			objectStore.setObjectType('event')
			await objectStore.refreshObjectList()
				.then(() => {
					// Create options from all available events
					this.events = {
						multiple: true,
						closeOnSelect: false,
						options: objectStore.objectList.map((event) => ({
							id: event.id,
							label: event.name,
						})),
					}

					// Pre-select existing events
					if (objectStore.objectItem?.events?.length) {
						this.selectedEvents = objectStore.objectItem.events.map(event => ({
							id: event.id || event,
							label: objectStore.objectList.find(e => e.id === (event.id || event))?.name || '',
						}))
					}

					this.eventsLoading = false
					
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
				.catch((error) => {
					console.error('Error fetching events:', error)
					this.eventsLoading = false
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
		},
		async addEventsToCharacter() {
			this.loading = true
			try {
				const characterItemClone = { ...objectStore.objectItem }
				
				// Replace events array with selected events, ensuring uniqueness
				const uniqueEvents = [...new Map(this.selectedEvents.map(event => [event.id, event])).values()]
				
				// Store current object type
				const currentType = objectStore.objectType
				
				// Switch to event type to fetch full event data
				objectStore.setObjectType('event')
				await objectStore.refreshObjectList()
				
				characterItemClone.events = uniqueEvents.map(selected => {
					const eventData = objectStore.objectList.find(e => e.id === selected.id)
					return {
						objectType: 'event',
						id: eventData.id,
						name: eventData.name,
						description: eventData.description || '',
						effects: eventData.effects || [],
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
				this.error = error.message || 'Er is een fout opgetreden bij het bewerken van de evenementen'
			}
		},
		openLink(url, target) {
			window.open(url, target)
		},
	},
}
</script>
