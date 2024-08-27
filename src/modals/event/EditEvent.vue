<script setup>
import { eventStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcDialog v-if="navigationStore.modal === 'editEvent'"
		name="Event"
		size="normal"
		:can-close="false">

		<NcNoteCard v-if="success" type="success">
			<p>Event succesvol aangepast</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<div v-if="!success"  class="formContainer">
			<NcTextField :disabled="loading"
				label="Name *"
				required
				:value.sync="eventStore.eventItem.name" />
			<NcTextArea :disabled="loading"
				label="Description"
				type="textarea"
				:value.sync="eventStore.eventItem.description" />
			<NcTextField :disabled="loading"
				label="Start Date"
				type="date"
				:value.sync="eventStore.eventItem.startDate" />
			<NcTextField :disabled="loading"
				label="End Date"
				type="date"
				:value.sync="eventStore.eventItem.endDate" />
			<NcTextField :disabled="loading"
				label="Location"
				:value.sync="eventStore.eventItem.location" />
		</div>

		<template #actions>
			<NcButton @click="navigationStore.setModal(false)">
				<template #icon><Cancel :size="20" /></template>
				{{ success ? 'Sluiten' : 'Annuleer' }}
			</NcButton>
			<NcButton @click="openLink('https://conduction.gitbook.io/opencatalogi-nextcloud/gebruikers/publicaties', '_blank')">
				<template #icon><Help :size="20" /></template>
				Help
			</NcButton>
			<NcButton v-if="!success" :disabled="loading" type="primary" @click="editEvent()">
				<template #icon>
					<NcLoadingIcon v-if="loading" :size="20" />
					<ContentSaveOutline v-if="!loading && eventStore.eventItem.id" :size="20" />
					<Plus v-if="!loading && !eventStore.eventItem.id" :size="20" />
				</template>
				{{ eventStore.eventItem.id ? 'Opslaan' : 'Aanmaken' }}
			</NcButton>
		</template>
	</NcDialog>
</template>

<script>
import {
	NcButton, NcDialog, NcTextField, NcTextArea, NcLoadingIcon, NcNoteCard
} from '@nextcloud/vue'
import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'
import Cancel from 'vue-material-design-icons/Cancel.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Help from 'vue-material-design-icons/Help.vue'

export default {
	name: 'EditEvent',
	components: {
		NcDialog, NcTextField, NcTextArea, NcButton, NcLoadingIcon, NcNoteCard,
		ContentSaveOutline, Cancel, Plus, Help,
	},
	data() {
		return {
			success: false,
			loading: false,
			error: false,
		}
	},
	methods: {
		async editEvent() {
			this.loading = true
			try {
				await eventStore.saveEvent()
				this.success = true
				this.loading = false
				setTimeout(() => {
					this.success = false
					navigationStore.setModal(false)
				}, 2000)
			} catch (error) {
				this.loading = false
				this.success = false
				this.error = error.message || 'An error occurred while saving the event'
			}
		},
		openLink(url, target) {
			window.open(url, target)
		}
	},
}
</script>
