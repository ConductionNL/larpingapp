<template>
	<NcContent app-name="zaakafhandelapp">
		<MainMenu />
		<Views />
		<Modals />
	</NcContent>
</template>

<script>

import { NcContent } from '@nextcloud/vue'
import MainMenu from './navigation/MainMenu.vue'
import Modals from './modals/Modals.vue'
import Views from './views/Views.vue'

export default {
	name: 'App',
	components: {
		NcContent,
		MainMenu,
		Modals,
		Views,
	},
}
</script>
