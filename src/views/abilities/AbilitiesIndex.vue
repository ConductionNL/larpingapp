<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'
import { onMounted } from 'vue'
</script>

<template>
	<NcAppContent>
		<template #list>
			<AbilitiesList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!objectStore.objectItem || navigationStore.selected != 'abilities'"
				class="detailContainer"
				name="Geen vaardigheid"
				description="Nog geen vaardigheid geselecteerd">
				<template #icon>
					<ShieldSwordOutline />
				</template>
				<template #action>
					<NcButton type="primary" @click="objectStore.setObjectItem(null); navigationStore.setModal('editAbility')">
						Vaardigheid aanmaken
					</NcButton>
				</template>
			</NcEmptyContent>
			<AbilityDetails v-if="objectStore.objectItem && navigationStore.selected === 'abilities'" />
		</template>
	</NcAppContent>
</template>

<script>
import { NcAppContent, NcEmptyContent, NcButton } from '@nextcloud/vue'
import AbilitiesList from './AbilitiesList.vue'
import AbilityDetails from './AbilityDetails.vue'
// eslint-disable-next-line n/no-missing-import
import ShieldSwordOutline from 'vue-material-design-icons/ShieldSwordOutline'

const objectStore = useObjectStore()

// Set the object type to 'ability'
onMounted(() => {
	objectStore.setObjectType('ability')
})

export default {
	name: 'AbilitiesIndex',
	components: {
		NcAppContent,
		NcEmptyContent,
		NcButton,
		AbilitiesList,
		AbilityDetails,
		ShieldSwordOutline,
	},
	data() {
		return {
			RolId: undefined,
		}
	},
}
</script>
