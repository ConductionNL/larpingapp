<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcAppContent>
		<template #list>
			<CharactersList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!store.characterItem || store.selected != 'characters' "
				class="detailContainer"
				name="Geen Karakter"
				description="Nog geen karakter geselecteerd">
				<template #icon>
					<BriefcaseAccountOutline />
				</template>
				<template #action>
					<NcButton type="primary" @click="store.setModal('addCharacter')">
						Karakter aanmaken
					</NcButton>
				</template>
			</NcEmptyContent>
			<CharacterDetails v-if="store.characterItem && store.selected === 'characters'" />
		</template>
	</NcAppContent>
</template>

<script>
import { NcAppContent, NcEmptyContent, NcButton } from '@nextcloud/vue'
import CharactersList from './CharactersList.vue'
import CharacterDetails from './CharacterDetails.vue'
import BriefcaseAccountOutline from 'vue-material-design-icons/BriefcaseAccountOutline.vue'

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
