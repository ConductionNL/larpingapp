import { SafeParseReturnType, z } from 'zod'
import { TCondition } from './condition.types'

export class Condition implements TCondition {
	id?: string
	name: string
	description?: string
	effect?: string
	effects?: string[] // Array of Effect UUIDs
	unique: boolean
	characters?: string[] // Array of Character UUIDs

	constructor(condition: TCondition) {
		this.id = condition.id
		this.name = condition.name
		this.description = condition.description
		this.effect = condition.effect
		this.effects = condition.effects
		this.unique = condition.unique ?? false // Set default value to false if not provided
		this.characters = condition.characters
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TCondition, unknown> {
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