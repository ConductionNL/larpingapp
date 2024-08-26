import { SafeParseReturnType, z } from 'zod'
import { TAbility } from './ability.types'

export class Ability implements TAbility {

	public id: string
	public name: string
	public description?: string
	public base: number

	constructor(data: TAbility) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TAbility) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
		this.description = data?.description
		this.base = data?.base ?? 0 // Set default value to 0 if not provided
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TAbility, unknown> {
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