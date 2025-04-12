<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<div class="detailContainer">
		<div id="app-content">
			<!-- app-content-wrapper is optional, only use if app-content-list  -->
			<div>
				<div class="head">
					<h1 class="h1">
						{{ objectStore.objectItem.name }}
					</h1>
					<NcActions :primary="true" menu-name="Acties">
						<template #icon>
							<DotsHorizontal :size="20" />
						</template>
						<NcActionButton @click="navigationStore.setModal('editCondition')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="navigationStore.setDialog('deleteCondition')">
							<template #icon>
								<TrashCanOutline :size="20" />
							</template>
							Verwijderen
						</NcActionButton>
					</NcActions>
				</div>
				<div class="detailGrid">
					<div>
						<b>Sammenvatting:</b>
						<span>{{ objectStore.objectItem.summary }}</span>
					</div>
				</div>
				<span>{{ objectStore.objectItem.description }}</span>
				<div class="tabContainer">
					<BTabs content-class="mt-3" justified>
						<BTab>
							<template #title>
								Effects <NcCounterBubble>{{ objectStore.objectItem?.effects?.length || 0 }}</NcCounterBubble>
							</template>
							<div v-if="objectStore.objectItem?.effects?.length > 0">
								<NcListItem v-for="(effect) in objectStore.objectItem.effects"
									:key="effect.id"
									:name="effect.name"
									:bold="false"
									:force-display-actions="true"
									:details="effect?.modification || ''"
									:counter-number="effect?.modifier">
									<template #icon>
										<MagicStaff disable-menu
											:size="44" />
									</template>
									<template #subname>
										{{ effect.name }}
									</template>
								</NcListItem>
							</div>
							<div v-else>
								Geen effects gevonden
							</div>
						</BTab>
						<BTab>
							<template #title>
								Characters <NcCounterBubble>{{ objectStore.relations ? objectStore.relations.length : 0 }}</NcCounterBubble>
							</template>
							<ObjectList :objects="objectStore.relations" />							
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

<script>
// Components
import { BTabs, BTab } from 'bootstrap-vue'
import { NcLoadingIcon, NcActions, NcActionButton, NcListItem, NcCounterBubble } from '@nextcloud/vue'

// Custom components
import AuditList from '../auditTrail/AuditList.vue'
import ObjectList from '../objects/ObjectList.vue'

// Icons
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import MagicStaff from 'vue-material-design-icons/MagicStaff.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'ConditionDetails',
	components: {
		BTabs,
		BTab,
		NcLoadingIcon,
		NcActions,
		NcActionButton,
		NcListItem,
		NcCounterBubble,
		AuditList,
		ObjectList,
		// Icons
		DotsHorizontal,
		Pencil,
		MagicStaff,
		TrashCanOutline,
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

/* Add margin to counter bubble only when inside nav-item */
.nav-item .counter-bubble__counter {
    margin-left: 10px;
}

</style>
