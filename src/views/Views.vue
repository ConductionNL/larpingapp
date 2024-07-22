<script setup>
import { store } from '../store.js'
</script>

<template>
	<!-- Placeholder div for all of the vieuws-->
	<NcAppContent>
		<template #default>
			<DashboardIndex v-if="store.selected === 'dashboard'" />
			<CharactersIndex v-if="store.selected === 'characters'" />
			<PlayersIndex v-if="store.selected === 'players'" />
			<ItemsIndex v-if="store.selected === 'items'" />
			<SearchIndex v-if="store.selected === 'search'" />
			<EventsIndex v-if="store.selected === 'events'" />
			<AbilitiesIndex v-if="store.selected === 'abilities'" />
			<SkillsIndex v-if="store.selected === 'skills'" />
			<EffectsIndex v-if="store.selected === 'effects'" />
			<AuditTrailIndex v-if="store.selected === 'auditTrail'" />
		</template>
	</NcAppContent>
</template>

<script>
import { NcAppContent } from '@nextcloud/vue'
import DashboardIndex from './dashboard/DashboardIndex.vue'
import SearchIndex from './search/SearchIndex.vue'
import CharactersIndex from './characters/CharactersIndex.vue'
import PlayersIndex from './players/PlayersIndex.vue'
import ItemsIndex from './items/ItemsIndex.vue'
import EventsIndex from './events/EventsIndex.vue'
import AbilitiesIndex from './abilities/AbilitiesIndex.vue'
import SkillsIndex from './skills/SkillsIndex.vue'
import EffectsIndex from './effects/EffectsIndex.vue'

export default {
	name: 'Views',
	components: {
		DashboardIndex,
		SearchIndex,
		CharactersIndex,
		PlayersIndex,
		ItemsIndex,
		EventsIndex,
		AbilitiesIndex,
		SkillsIndex,
		EffectsIndex,
	},
}
</script>
