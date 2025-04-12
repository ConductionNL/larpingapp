<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'
import { onMounted } from 'vue'
</script>

<template>
	<NcAppContent>
		<template #list>
			<CharactersList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!objectStore.objectItem || navigationStore.selected != 'characters'"
				class="detailContainer"
				name="Geen Karakter"
				description="Nog geen karakter geselecteerd">
				<template #icon>
					<BriefcaseAccountOutline />
				</template>
				<template #action>
					<NcButton type="primary" @click="objectStore.setObjectItem(null); navigationStore.setModal('editCharacter')">
						Karakter aanmaken
					</NcButton>
				</template>
			</NcEmptyContent>
			<CharacterDetails v-if="objectStore.objectItem && navigationStore.selected === 'characters'" />
		</template>
	</NcAppContent>
</template>

<script>
import { NcAppContent, NcEmptyContent, NcButton } from '@nextcloud/vue'
import CharactersList from './CharactersList.vue'
import CharacterDetails from './CharacterDetails.vue'
import BriefcaseAccountOutline from 'vue-material-design-icons/BriefcaseAccountOutline.vue'

const objectStore = useObjectStore()

// Set the object type to 'character'
onMounted(() => {
	objectStore.setObjectType('character')
})

export default {
	name: 'CharactersIndex',
	components: {
		NcAppContent,
		NcEmptyContent,
		NcButton,
		CharactersList,
		CharacterDetails,
		BriefcaseAccountOutline,
	},
}
</script>
