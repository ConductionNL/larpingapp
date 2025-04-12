<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcDialog v-if="navigationStore.modal === 'addSkillToCharacter'"
		name="Skills toevoegen"
		size="normal"
		:can-close="false"
		@close="closeModal">
		<NcNoteCard v-if="success" type="success">
			<p>Skills succesvol toegevoegd</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<div v-if="!success" class="formContainer">
			<NcSelect v-bind="skills"
				v-model="selectedSkills"
				input-label="Skills *"
				:loading="skillsLoading"
				:disabled="skillsLoading || loading" />
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
				@click="addSkillsToCharacter()">
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
	name: 'AddSkillToCharacter',
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
			skills: {},
			selectedSkills: [],
			skillsLoading: false,
			success: false,
			loading: false,
			error: false,
			hasUpdated: false,
		}
	},
	mounted() {
		this.fetchSkills()
	},
	updated() {
		if (navigationStore.modal === 'addSkillToCharacter' && !this.hasUpdated) {
			this.fetchSkills()
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
			this.selectedSkills = []
		},
		async fetchSkills() {
			this.skillsLoading = true

			// Store current object type
			const currentType = objectStore.objectType
			
			// Switch to skill type to fetch skills
			objectStore.setObjectType('skill')
			await objectStore.refreshObjectList()
				.then(() => {
					// Create options from all available skills
					this.skills = {
						multiple: true,
						closeOnSelect: false,
						options: objectStore.objectList.map((skill) => ({
							id: skill.id,
							label: skill.name,
						})),
					}

					// Pre-select existing skills
					if (objectStore.objectItem?.skills?.length) {
						this.selectedSkills = objectStore.objectItem.skills.map(skill => ({
							id: skill.id || skill,
							label: objectStore.objectList.find(s => s.id === (skill.id || skill))?.name || '',
						}))
					}

					this.skillsLoading = false
					
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
				.catch((error) => {
					console.error('Error fetching skills:', error)
					this.skillsLoading = false
					// Restore previous object type
					objectStore.setObjectType(currentType)
				})
		},
		async addSkillsToCharacter() {
			this.loading = true
			try {
				const characterItemClone = { ...objectStore.objectItem }
				
				// Replace skills array with selected skills
				const uniqueSkills = [...new Map(this.selectedSkills.map(skill => [skill.id, skill])).values()]
				
				// Store current object type
				const currentType = objectStore.objectType
				
				// Switch to skill type to fetch full skill data
				objectStore.setObjectType('skill')
				await objectStore.refreshObjectList()
				
				characterItemClone.skills = uniqueSkills.map(selected => {
					const skillData = objectStore.objectList.find(s => s.id === selected.id)
					return {
						objectType: 'skill',
						id: skillData.id,
						name: skillData.name,
						description: skillData.description || '',
						requiredScore: skillData.requiredScore || '',
						effects: skillData.effects || [],
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
				this.error = error.message || 'Er is een fout opgetreden bij het bewerken van de vaardigheden'
			}
		},
		openLink(url, target) {
			window.open(url, target)
		},
	},
}
</script>
