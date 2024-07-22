<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcAppContent>
		<template #list>
			<EventsList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!store.eventItem || !store.eventItem.id || store.selected != 'events' "
				class="detailContainer"
				name="Geen event"
				description="Nog geen event geselecteerd">
				<template #icon>
					<CalendarMonthOutline />
				</template>
				<template #action>
					<NcButton type="primary" @click="store.setModal('addEvents')">
						Event toevoegen
					</NcButton>
				</template>
			</NcEmptyContent>
			<EventDetails v-if="store.eventItem && store.eventItem.id && store.selected === 'events'" />
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
