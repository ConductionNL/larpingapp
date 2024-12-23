import { SafeParseReturnType, z } from 'zod'
import { TEffect } from './effect.types'

export class Effect implements TEffect {

	public id: string
	public name: string
	public description: string
	public modifier: number
	public modification: 'positive' | 'negative'
	public cumulative: 'cumulative' | 'non-cumulative'
	public abilities: string[]

	constructor(effect: TEffect) {
		this.id = effect.id || ''
		this.name = effect.name || ''
		this.description = effect.description || ''
		this.modifier = effect.modifier || 0
		this.modification = effect.modification || 'positive'
		this.cumulative = effect.cumulative || 'non-cumulative'
		this.abilities = effect.abilities || []
	}

	/* istanbul ignore next */
	private hydrate(data: TEffect) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
		this.description = data?.description || ''
		this.modifier = data?.modifier || 0
		this.modification = data?.modification || 'positive'
		this.cumulative = data?.cumulative || 'non-cumulative'
		this.abilities = data?.abilities || []
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TEffect, unknown> {
		const schema = z.object({
			name: z.string().min(1),
		})

		return schema.safeParse({ ...this })
	}

}
