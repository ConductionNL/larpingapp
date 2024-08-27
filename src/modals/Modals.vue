<template>
	<!-- Placeholder -->
	<div>
		<EditAbility />
		<EditCharacter />
		<DeleteCharacter />
		<EditCondition />
		<EditEffect />
		<EditEvent />
		<EditItem />
		<EditPlayer />
		<EditSkill />
		<EditTemplate />
	</div>
</template>

<script>
import EditAbility from './ability/EditAbility.vue'
import EditCharacter from './character/EditCharacter.vue'
import DeleteCharacter from './character/DeleteCharacter.vue'
import EditCondition from './condition/EditCondition.vue'
import EditEffect from './effect/EditEffect.vue'
import EditEvent from './event/EditEvent.vue'
import EditItem from './item/EditItem.vue'
import EditPlayer from './player/EditPlayer.vue'
import EditSkill from './skill/EditSkill.vue'
import EditTemplate from './template/EditTemplate.vue'

export default {
	name: 'Modals',
	components: {
		EditAbility,
		EditCharacter,
		DeleteCharacter,
		EditCondition,
		EditEffect,
		EditEvent,
		EditItem,
		EditSkill,
		EditPlayer,
		EditTemplate,
	},
}
</script>
