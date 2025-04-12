<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'
import { onMounted } from 'vue'

const objectStore = useObjectStore()

// Set the object type to 'event'
onMounted(() => {
	objectStore.setObjectType('event')
})
</script>

<template>
	<NcAppContent>
		<template #list>
			<EventsList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!objectStore.objectItem || navigationStore.selected != 'events'"
				class="detailContainer"
				name="Geen event"
				description="Nog geen event geselecteerd">
				<template #icon>
					<CalendarMonthOutline />
				</template>
				<template #action>
					<NcButton type="primary" @click="objectStore.setObjectItem(null); navigationStore.setModal('editEvent')">
						Event toevoegen
					</NcButton>
				</template>
			</NcEmptyContent>
			<EventDetails v-if="objectStore.objectItem && navigationStore.selected === 'events'" />
		</template>
	</NcAppContent>
</template>

<script>
import { NcAppContent, NcEmptyContent, NcButton } from '@nextcloud/vue'
import EventsList from './EventsList.vue'
import EventDetails from './EventDetails.vue'
// eslint-disable-next-line n/no-missing-import
import CalendarMonthOutline from 'vue-material-design-icons/CalendarMonthOutline'

export default {
	name: 'EventsIndex',
	components: {
		NcAppContent,
		NcEmptyContent,
		NcButton,
		EventsList,
		EventDetails,
		CalendarMonthOutline,
	},
}
</script>
