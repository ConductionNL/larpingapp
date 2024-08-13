<template>
	<!-- Placeholder -->
	<div>
		<AddAbility />
		<EditAbility />
		<AddCharacter />
		<EditCharacter />
		<AddCondition />
		<EditCondition />
		<AddEvent />
		<EditEvent />
		<AddItem />
		<EditItem />
		<AddPlayer />
		<EditPlayer />
		<AddSkill />
		<EditSkill />
		<AddTemplate />
		<EditTemplate />
	</div>
</template>

<script>
import AddAbility from './ability/AddAbility.vue'
import EditAbility from './ability/EditAbility.vue'
import AddCharacter from './character/AddCharacter.vue'
import EditCharacter from './character/EditCharacter.vue'
import AddCondition from './condition/AddCondition.vue'
import EditCondition from './condition/EditCondition.vue'
import AddEvent from './event/AddEvent.vue'
import EditEvent from './event/EditEvent.vue'
import AddItem from './item/AddItem.vue'
import EditItem from './item/EditItem.vue'
import AddPlayer from './player/AddPlayer.vue'
import EditPlayer from './player/EditPlayer.vue'
import AddSkill from './skill/AddSkill.vue'
import EditSkill from './skill/EditSkill.vue'
import AddTemplate from './template/AddTemplate.vue'
import EditTemplate from './template/EditTemplate.vue'

export default {
	name: 'Modals',
	components: {
		AddAbility,
		EditAbility,
		AddCharacter,
		EditCharacter,
		AddCondition,
		EditCondition,
		AddEvent,
		EditEvent,
		AddItem,
		EditItem,
		AddPlayer,
		AddSkill,
		EditSkill,
		EditPlayer,
		AddTemplate,
		EditTemplate,
	},
}
</script>
