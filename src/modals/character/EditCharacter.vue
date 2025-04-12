<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore, playerStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcDialog v-if="navigationStore.modal === 'editCharacter'"
		name="Karakter"
		size="normal"
		:can-close="false"
		@close="closeModal">
		<NcNoteCard v-if="success" type="success">
			<p>Karakter succesvol aangepast</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<div v-if="!success" class="formContainer">
			<NcTextField :disabled="loading"
				label="Name *"
				required
				:value.sync="characterItem.name" />
			<NcTextArea :disabled="loading"
				label="Description"
				:value.sync="characterItem.description" />
			<NcTextArea :disabled="loading"
				label="Background"
				:value.sync="characterItem.background" />
			<NcTextArea :disabled="loading"
				label="Notice"
				:value.sync="characterItem.notice" />
			<NcSelect v-bind="players"
				v-model="players.value"
				input-label="OC Name *"
				:loading="playersLoading"
				:disabled="playersLoading || loading" />
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
				@click="editCharacter()">
				<template #icon>
					<NcLoadingIcon v-if="loading" :size="20" />
					<ContentSaveOutline v-if="!loading && characterItem.id" :size="20" />
					<Plus v-if="!loading && !characterItem.id" :size="20" />
				</template>
				{{ characterItem.id ? 'Opslaan' : 'Aanmaken' }}
			</NcButton>
		</template>
	</NcDialog>
</template>

<script>
import {
	NcButton,
	NcDialog,
	NcTextField,
	NcTextArea,
	NcSelect,
	NcLoadingIcon,
	NcNoteCard,
} from '@nextcloud/vue'

import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'
import Cancel from 'vue-material-design-icons/Cancel.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Help from 'vue-material-design-icons/Help.vue'

export default {
	name: 'EditCharacter',
	components: {
		NcDialog,
		NcTextField,
		NcTextArea,
		NcButton,
		NcSelect,
		NcLoadingIcon,
		NcNoteCard,
		// Icons
		ContentSaveOutline,
		Cancel,
		Plus,
		Help,
	},
	data() {
		return {
			characterItem: {
				name: '',
				ocName: '',
				description: '',
				background: '',
				notice: '',
			},
			players: {},
			playersLoading: false,
			success: false,
			loading: false,
			error: false,
			hasUpdated: false,
		}
	},
	mounted() {
		this.fetchPlayers()
	},
	updated() {
		if (navigationStore.modal === 'editCharacter' && !this.hasUpdated) {
			if (objectStore.objectItem?.id) {
				this.characterItem = {
					...objectStore.objectItem,
					name: objectStore.objectItem.name || '',
					description: objectStore.objectItem.description || '',
					background: objectStore.objectItem.background || '',
					notice: objectStore.objectItem.notice || '',
				}
			}
			this.fetchPlayers()
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
			this.characterItem = {
				name: '',
				ocName: '',
				description: '',
				background: '',
				notice: '',
			}
		},
		fetchPlayers() {
			this.playersLoading = true

			playerStore.refreshPlayerList()
				.then(() => {
					const activatedPlayer = objectStore.objectItem?.id 
						? playerStore.playerList.find((player) => 
							objectStore.objectItem.ocName?.id === player.id ||
							objectStore.objectItem.ocName?.toString() === player.id.toString()
						)
						: null

					const mappedActivatedPlayer = activatedPlayer
						? {
							id: activatedPlayer.id.toString(),
							label: activatedPlayer.name,
						}
						: null

					this.players = {
						options: playerStore.playerList.map((player) => ({
							id: player.id.toString(),
							label: player.name,
						})),
						value: mappedActivatedPlayer,
					}

					this.playersLoading = false
				})
				.catch((error) => {
					console.error('Error fetching players:', error)
					this.playersLoading = false
				})
		},
		async editCharacter() {
			this.loading = true
			try {
				// Set the object type to 'character' before saving
				objectStore.setObjectType('character')
				
				await objectStore.saveObject({
					...this.characterItem,
					ocName: this.players?.value?.id || null,
				})
				
				// Close modal or show success message
				this.success = true
				this.loading = false
				this.error = false
				setTimeout(() => {
					this.closeModal()
					navigationStore.setSelected('characters')
				}, 2000)
			} catch (error) {
				this.loading = false
				this.success = false
				this.error = error.message || 'An error occurred while saving the character'
			}
		},
	},
}
</script>
