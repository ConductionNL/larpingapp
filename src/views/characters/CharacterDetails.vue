<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'
import { BTabs, BTab } from 'bootstrap-vue'
import { NcActions, NcActionButton, NcListItem, NcNoteCard, NcCounterBubble } from '@nextcloud/vue'
import AuditList from '../auditTrail/AuditList.vue'
import ObjectList from '../objects/ObjectList.vue'
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import AccountPlus from 'vue-material-design-icons/AccountPlus.vue'
import CalendarPlus from 'vue-material-design-icons/CalendarPlus.vue'
import FileDocumentPlusOutline from 'vue-material-design-icons/FileDocumentPlusOutline.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'
import EmoticonSickOutline from 'vue-material-design-icons/EmoticonSickOutline.vue'
import ShieldSwordOutline from 'vue-material-design-icons/ShieldSwordOutline.vue'
import Download from 'vue-material-design-icons/Download.vue'
import AccountCheck from 'vue-material-design-icons/AccountCheck.vue'

const objectStore = useObjectStore()

/**
 * Downloads the current character as a PDF
 */
function downloadCharacterPdf() {
	const characterId = objectStore.objectItem.id
	fetch(`characters/${characterId}/download`)
		.then(response => {
			if (!response.ok) {
				throw new Error('Network response was not ok')
			}
			return response.blob()
		})
		.then(blob => {
			const link = document.createElement('a')
			link.href = window.URL.createObjectURL(blob)
			link.download = `${objectStore.objectItem.name}_character_sheet.pdf`
			link.click()
			window.URL.revokeObjectURL(link.href)
		})
		.catch(error => {
			console.error('Error downloading PDF:', error)
			// Handle error (e.g., show error message to user)
		})
}
</script>

<template>
	<div class="detailContainer">
		<div id="app-content">
			<div>
				<div class="head">
					<h1 class="h1">
						{{ objectStore.objectItem.name }}
					</h1>
					<NcActions :primary="true" menu-name="Acties">
						<template #icon>
							<DotsHorizontal :size="20" />
						</template>
						<NcActionButton @click="navigationStore.setModal('editCharacter')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Character Bewerken
						</NcActionButton>
						<NcActionButton @click="navigationStore.setModal('addSkillToCharacter')">
							<template #icon>
								<FileDocumentPlusOutline :size="20" />
							</template>
							Skills bewerken
						</NcActionButton>
						<NcActionButton @click="navigationStore.setModal('addItemToCharacter')">
							<template #icon>
								<AccountPlus :size="20" />
							</template>
							Items bewerken
						</NcActionButton>
						<NcActionButton @click="navigationStore.setModal('addConditionToCharacter')">
							<template #icon>
								<EmoticonSickOutline :size="20" />
							</template>
							Condities bewerken
						</NcActionButton>
						<NcActionButton @click="navigationStore.setModal('addEventToCharacter')">
							<template #icon>
								<CalendarPlus :size="20" />
							</template>
							Events bewerken
						</NcActionButton>
						<NcActionButton @click="downloadCharacterPdf">
							<template #icon>
								<Download :size="20" />
							</template>
							Als pdf downloaden
						</NcActionButton>
						<NcActionButton>
							<template #icon>
								<AccountCheck :size="20" />
							</template>
							Accoderen
						</NcActionButton>
						<NcActionButton @click="navigationStore.setDialog('deleteCharacter')">
							<template #icon>
								<TrashCanOutline :size="20" />
							</template>
							Verwijderen
						</NcActionButton>
					</NcActions>
				</div>
				<NcNoteCard v-if="objectStore.objectItem.notice" type="info">
					{{ objectStore.objectItem.notice }}
				</NcNoteCard>
				<div class="detailGrid">
					<div>
						<b>Sammenvatting:</b>
						<span>{{ objectStore.objectItem.summary }}</span>
					</div>
				</div>
				<span>{{ objectStore.objectItem.description }}</span>
				<div class="tabContainer">
					<BTabs content-class="mt-3" justified>
						<BTab active>
							<template #title>
								Eigenschappen <NcCounterBubble>{{ objectStore.objectItem.stats ? Object.keys(objectStore.objectItem.stats).length : 0 }}</NcCounterBubble>
							</template>
							<div v-if="objectStore.objectItem.stats">
								<NcListItem v-for="(stat, id) in objectStore.objectItem.stats"
									:key="id"
									:name="stat.name"
									:bold="false">
									<template #icon>
										<ShieldSwordOutline :size="44" />
									</template>
									<template #subname>
										Score: {{ stat.value }}
									</template>
								</NcListItem>
							</div>
							<div v-else>
								Geen eigenschappen gevonden
							</div>
						</BTab>

						<BTab>
							<template #title>
								Skills <NcCounterBubble>{{ objectStore.objectItem.skills ? objectStore.objectItem.skills.length : 0 }}</NcCounterBubble>
							</template>
							<ObjectList :objects="objectStore.objectItem.skills" />
						</BTab>

						<BTab>
							<template #title>
								Items <NcCounterBubble>{{ objectStore.objectItem.items ? objectStore.objectItem.items.length : 0 }}</NcCounterBubble>
							</template>
							<ObjectList :objects="objectStore.objectItem.items" />
						</BTab>

						<BTab>
							<template #title>
								Conditions <NcCounterBubble>{{ objectStore.objectItem.conditions ? objectStore.objectItem.conditions.length : 0 }}</NcCounterBubble>
							</template>
							<ObjectList :objects="objectStore.objectItem.conditions" />
						</BTab>

						<BTab>
							<template #title>
								Events <NcCounterBubble>{{ objectStore.objectItem.events ? objectStore.objectItem.events.length : 0 }}</NcCounterBubble>
							</template>
							<ObjectList :objects="objectStore.objectItem.events" />
						</BTab>

						<BTab>
							<template #title>
								Background <NcCounterBubble>{{ objectStore.objectItem.background ? 1 : 0 }}</NcCounterBubble>
							</template>
							<div v-if="objectStore.objectItem.background">
								{{ objectStore.objectItem.background }}
							</div>
							<div v-else>
								Geen achtergrond gevonden
							</div>
						</BTab>

						<BTab>
							<template #title>
								Logging <NcCounterBubble>{{ objectStore.auditTrails ? objectStore.auditTrails.length : 0 }}</NcCounterBubble>
							</template>
							<AuditList :logs="objectStore.auditTrails" />
						</BTab>
					</BTabs>
				</div>
			</div>
		</div>
	</div>
</template>

<style>
h4 {
  font-weight: bold;
}

.head{
	display: flex;
	justify-content: space-between;
}

.button{
	max-height: 10px;
}

.h1 {
  display: block !important;
  font-size: 2em !important;
  margin-block-start: 0.67em !important;
  margin-block-end: 0.67em !important;
  margin-inline-start: 0px !important;
  margin-inline-end: 0px !important;
  font-weight: bold !important;
  unicode-bidi: isolate !important;
}

.dataContent {
  display: flex;
  flex-direction: column;
}

/* Add margin to counter bubble only when inside nav-item */
.nav-item .counter-bubble__counter {
    margin-left: 10px;
}
</style>
