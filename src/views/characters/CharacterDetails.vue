<script setup>
import { characterStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<div class="detailContainer">
		<div id="app-content">
			<!-- app-content-wrapper is optional, only use if app-content-list  -->
			<div>
				<div class="head">
					<h1 class="h1">
						{{ characterStore.characterItem.name }}
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
								<CalendarPlus :size="20" />
							</template>
							Condities bewerken
						</NcActionButton>
						<NcActionButton @click="navigationStore.setModal('addEventToCharacter')">
							<template #icon>
								<CalendarPlus :size="20" />
							</template>
							Events bewerken
						</NcActionButton>
						<NcActionButton @click="navigationStore.setModal('renderPdfFromCharacter')">
							<template #icon>
								<Download :size="20" />
							</template>
							Als pdf downloaden
						</NcActionButton>
						<NcActionButton >
							<template #icon>
								<Download :size="20" />
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
				<NcNoteCard v-if="characterStore.characterItem.notice" type="info">
					{{ characterStore.characterItem.notice }}
				</NcNoteCard>
				<div class="detailGrid">
					<div>
						<b>Sammenvatting:</b>
						<span>{{ characterStore.characterItem.summary }}</span>
					</div>
				</div>
				<span>{{ characterStore.characterItem.description }}</span>
				<div class="tabContainer">
					<BTabs content-class="mt-3" justified>
						<BTab title="Eigenschappen" active>
							<div v-if="characterStore.characterItem.stats">
								<NcListItem v-for="(stat, id) in characterStore.characterItem.stats"
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

						<BTab title="Skills">							
							<ObjectList :objects="characterStore.characterItem.skills" />
						</BTab>

						<BTab title="Items">
							<ObjectList :objects="characterStore.characterItem.items" />
						</BTab>

						<BTab title="Conditions">							
							<ObjectList :objects="characterStore.characterItem.conditions" />
						</BTab>

						<BTab title="Events">
							<ObjectList :objects="characterStore.characterItem.events" />
						</BTab>

						<BTab title="Background">
							<div v-if="characterStore.characterItem.background">
								{{ characterStore.characterItem.background }}
							</div>
							<div v-else>
								Geen achtergrond gevonden
							</div>
						</BTab>
						
						<BTab title="Logging">
							<AuditList :logs="characterStore.auditTrails" />
						</BTab>
					</BTabs>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
// Components
import { BTabs, BTab } from 'bootstrap-vue'
import { NcActions, NcActionButton, NcListItem, NcNoteCard } from '@nextcloud/vue'

// Custom components
import AuditList from '../auditTrail/AuditList.vue'
import ObjectList from '../objects/ObjectList.vue'

// Icons
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import AccountPlus from 'vue-material-design-icons/AccountPlus.vue'
import CalendarPlus from 'vue-material-design-icons/CalendarPlus.vue'
import MessagePlus from 'vue-material-design-icons/MessagePlus.vue'
import FileDocumentPlusOutline from 'vue-material-design-icons/FileDocumentPlusOutline.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'
import EyeArrowRight from 'vue-material-design-icons/EyeArrowRight.vue'
import SwordCross from 'vue-material-design-icons/SwordCross.vue'
import Sword from 'vue-material-design-icons/Sword.vue'
import EmoticonSickOutline from 'vue-material-design-icons/EmoticonSickOutline.vue'
import CalendarMonthOutline from 'vue-material-design-icons/CalendarMonthOutline.vue'
import ShieldSwordOutline from 'vue-material-design-icons/ShieldSwordOutline.vue'
import Download from 'vue-material-design-icons/Download.vue'
import BriefcaseAccountOutline from 'vue-material-design-icons/BriefcaseAccountOutline.vue'
export default {
	name: 'CharacterDetails',
	components: {
		// Components
		NcActions,
		NcActionButton,
		NcListItem,
		NcNoteCard,
		BTabs,
		BTab,
		// Custom components
		AuditList,
		ObjectList,
		// Icons
		DotsHorizontal,
		Pencil,
		AccountPlus,
		CalendarPlus,
		FileDocumentPlusOutline,
		TrashCanOutline,
		EyeArrowRight,
		SwordCross,
		Sword,
		EmoticonSickOutline,
		CalendarMonthOutline,
		ShieldSwordOutline,
		Download,
		BriefcaseAccountOutline
	},
	methods: {
		downloadCharacterPdf() {
			const characterId = characterStore.characterItem.id
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
					link.download = `${characterStore.characterItem.name}_character_sheet.pdf`
					link.click()
					window.URL.revokeObjectURL(link.href)
				})
				.catch(error => {
					console.error('Error downloading PDF:', error)
					// Handle error (e.g., show error message to user)
				})
		},
	},
}
</script>

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

</style>
