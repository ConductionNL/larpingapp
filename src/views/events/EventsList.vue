<script setup>
	import { eventStore, navigationStore, searchStore } from '../../store/store.js'
</script>

<template>
	<NcAppContentList>
		<ul>
			<div class="listHeader">
				<NcTextField
					:value.sync="searchStore.search"
					:show-trailing-button="search !== ''"
					label="Search"
					class="searchField"
					trailing-button-icon="close"
					@trailing-button-click="searchStore.setSearch('')">
					<Magnify :size="20" />
				</NcTextField>
				<NcActions>
					<NcActionButton @click="store.getTakenList()">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="store.setModal('addTaak')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Taak toevoegen
					</NcActionButton>
				</NcActions>
			</div>
			<div v-if="eventStore.takenList">
				<NcListItem v-for="(taak, i) in store.takenList.results"
					:key="`${taak}${i}`"
					:name="taak?.title"
					:force-display-actions="true"
					:active="store.taakId === taak?.id"
					:details="'1h'"
					:counter-number="44"
					@click="store.setTaakItem(taak)">
					<template #icon>
						<CalendarMonthOutline :class="store.taakId === taak.id && 'selectedZaakIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ taak?.onderwerp }}
					</template>
					<template #actions>
						<NcActionButton @click="store.setTaakItem(taak); store.setModal('editTaak')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="store.setTaakItem(taak); store.setDialog('deleteTaak')">
							<template #icon>
								<TrashCanOutline :size="20" />
							</template>
							Verwijderen
						</NcActionButton>
					</template>
				</NcListItem>
			</div>
		</ul>

		<NcLoadingIcon v-if="!store.takenList"
			class="loadingIcon"
			:size="64"
			appearance="dark"
			name="Taken aan het laden" />
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
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		CalendarMonthOutline,
		Magnify,
		Pencil,
		TrashCanOutline,
	},
	data() {
		return {
			search: '',
			loading: true,
			takenList: [],
		}
	},
	mounted() {
		eventStore.refreshEventsList()
	},
	methods: {
		clearText() {
			this.search = ''
		},
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

.selectedZaakIcon>svg {
    fill: white;
}

.loadingIcon {
    margin-block-start: var(--zaa-margin-20);
}
</style>
