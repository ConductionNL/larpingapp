<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'

const objectStore = useObjectStore()
</script>

<template>
	<NcAppContentList>
		<ul>
			<div class="listHeader">
				<NcTextField
					:value="objectStore.searchTerm"
					:show-trailing-button="objectStore.searchTerm !== ''"
					label="Search"
					class="searchField"
					trailing-button-icon="close"
					@input="objectStore.setSearchTerm($event.target.value)"
					@trailing-button-click="objectStore.clearSearch()">
					<Magnify :size="20" />
				</NcTextField>
				<NcActions>
					<NcActionButton @click="objectStore.refreshObjectList()">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="objectStore.setObjectItem(null); navigationStore.setModal('editEvent')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Evenement toevoegen
					</NcActionButton>
				</NcActions>
			</div>
			<div v-if="objectStore.objectList && objectStore.objectList.length > 0 && !objectStore.isLoadingObjectList">
				<NcListItem v-for="(event, i) in objectStore.objectList"
					:key="`${event}${i}`"
					:name="event?.name"
					:force-display-actions="true"
					:active="objectStore.objectItem?.id === event?.id"
					:details="event.startDate"
					@click="objectStore.setObjectItem(event)">
					<template #icon>
						<CalendarMonthOutline :class="objectStore.objectItem?.id === event.id && 'selectedZaakIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ event?.description }}
					</template>
					<template #actions>
						<NcActionButton @click="objectStore.setObjectItem(event); navigationStore.setModal('editEvent')">
							<template #icon>
								<Pencil />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="objectStore.setObjectItem(event); navigationStore.setDialog('deleteEvent')">
							<template #icon>
								<TrashCanOutline />
							</template>
							Verwijderen
						</NcActionButton>
					</template>
				</NcListItem>
			</div>
		</ul>

		<NcLoadingIcon v-if="objectStore.isLoadingObjectList"
			class="loadingIcon"
			:size="64"
			appearance="dark"
			name="Evenementen aan het laden" />

		<div v-if="objectStore.objectList.length === 0 && !objectStore.isLoadingObjectList">
			Er zijn nog geen evenementen gedefinieerd.
		</div>
	</NcAppContentList>
</template>
<script>
// Components
import { NcListItem, NcActions, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'

// Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import CalendarMonthOutline from 'vue-material-design-icons/CalendarMonthOutline.vue'
import Refresh from 'vue-material-design-icons/Refresh.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'EventsList',
	components: {
		// Components
		NcListItem,
		NcActions,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		CalendarMonthOutline,
		Magnify,
		Plus,
		Pencil,
		TrashCanOutline,
		Refresh,
	},
	mounted() {
		objectStore.refreshObjectList()
	},
}
</script>

<style>
.listHeader {
	position: sticky;
	top: 0;
	z-index: 1000;
	background-color: var(--color-main-background);
	border-bottom: 1px solid var(--color-border);
}

.searchField {
	padding-inline-start: 65px;
	padding-inline-end: 20px;
	margin-block-end: 6px;
}

.selectedIcon>svg {
	fill: white;
}

.loadingIcon {
	margin-block-start: var(--OC-margin-20);
}
</style>
