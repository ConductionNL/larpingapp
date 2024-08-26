import { SafeParseReturnType, z } from 'zod'
import { TEffect } from './effect.types'

export class Effect implements TEffect {
	id?: string
	name: string
	description?: string
	stat?: string // UUID of the stat
	modifier?: number
	modification: 'positive' | 'negative'
	cumulative: 'cumulative' | 'non-cumulative'

	constructor(effect: TEffect) {
		this.id = effect.id
		this.name = effect.name
		this.description = effect.description
		this.stat = effect.stat
		this.modifier = effect.modifier
		this.modification = effect.modification ?? 'positive'
		this.cumulative = effect.cumulative ?? 'non-cumulative'
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TEffect, unknown> {
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