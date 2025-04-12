<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcDialog v-if="navigationStore.modal === 'addConditionToCharacter'"
		name="Conditions toevoegen"
		size="normal"
		:can-close="false"
		@close="closeModal">
		<NcNoteCard v-if="success" type="success">
			<p>Conditions succesvol toegevoegd</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<div v-if="!success" class="formContainer">
			<NcSelect v-bind="conditions"
				v-model="selectedConditions"
				input-label="Conditions *"
				:loading="conditionsLoading"
				:disabled="conditionsLoading || loading" />
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
				@click="addConditionsToCharacter()">
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
	name: 'AddConditionToCharacter',
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
			conditions: {},
			selectedConditions: [],
			conditionsLoading: false,
			success: false,
			loading: false,
			error: false,
			hasUpdated: false,
		}
	},
	mounted() {
		this.fetchConditions()
	},
	updated() {
		if (navigationStore.modal === 'addConditionToCharacter' && !this.hasUpdated) {
			this.fetchConditions()
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
			this.selectedConditions = []
		},
		async fetchConditions() {
			this.conditionsLoading = true

			// Store current object type
			const currentType = objectStore.objectType
			
			// Switch to condition type to fetch conditions
			objectStore.setObjectType('condition')
			await objectStore.refreshObjectList()
				.then(() => {
					// Create options from all available conditions
					this.conditions = {
						multiple: true,
						closeOnSelect: false,
						options: objectStore.objectList.map((condition) => ({
							id: condition.id,
							label: condition.name,
						})),
					}

					// Pre-select existing conditions
					if (objectStore.objectItem?.conditions?.length) {
						this.selectedConditions = objectStore.objectItem.conditions.map(condition => ({
							id: condition.id || condition,
							label: objectStore.objectList.find(c => c.id === (condition.id || condition))?.name || '',
						}))
					}

					this.conditionsLoading = false
					
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
				.catch((error) => {
					console.error('Error fetching conditions:', error)
					this.conditionsLoading = false
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
		},
		async addConditionsToCharacter() {
			this.loading = true
			try {
				const characterItemClone = { ...objectStore.objectItem }
				
				// Replace conditions array with selected conditions, ensuring uniqueness
				const uniqueConditions = [...new Map(this.selectedConditions.map(condition => [condition.id, condition])).values()]
				
				// Store current object type
				const currentType = objectStore.objectType
				
				// Switch to condition type to fetch full condition data
				objectStore.setObjectType('condition')
				await objectStore.refreshObjectList()
				
				characterItemClone.conditions = uniqueConditions.map(selected => {
					const conditionData = objectStore.objectList.find(c => c.id === selected.id)
					return {
						objectType: 'condition',
						id: conditionData.id,
						name: conditionData.name,
						description: conditionData.description || '',
						effects: conditionData.effects || [],
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
				this.error = error.message || 'Er is een fout opgetreden bij het bewerken van de condities'
			}
		},
		openLink(url, target) {
			window.open(url, target)
		},
	},
}
</script>
