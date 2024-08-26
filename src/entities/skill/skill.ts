import { SafeParseReturnType, z } from 'zod'
import { TSkill } from './skill.types'

export class Skill implements TSkill {
	id?: string
	name: string
	description?: string
	effect?: string
	effects?: string[] // Array of Effect UUIDs
	requiredSkills?: string[] // Array of Skill UUIDs
	requiredStats?: string[] // Array of Stat UUIDs
	requiredConditions?: string[] // Array of Condition UUIDs
	requiredEffects?: string[] // Array of Effect UUIDs
	requiredScore?: number

	constructor(skill: TSkill) {
		this.id = skill.id
		this.name = skill.name
		this.description = skill.description
		this.effect = skill.effect
		this.effects = skill.effects
		this.requiredSkills = skill.requiredSkills
		this.requiredStats = skill.requiredStats
		this.requiredConditions = skill.requiredConditions
		this.requiredEffects = skill.requiredEffects
		this.requiredScore = skill.requiredScore
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TSkill, unknown> {
		// https://conduction.stoplight.io/docs/open-catalogi/hpksgr0u1cwj8-theme
		const schema = z.object({
			name: z.string().min(1),
		})

		const result = schema.safeParse({
			...this,
		})

		return result
	}
}