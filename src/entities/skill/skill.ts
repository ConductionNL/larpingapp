import { SafeParseReturnType, z } from 'zod'
import { TSkill } from './skill.types'

export class Skill implements TSkill {

	public id: string
	public name: string

	constructor(data: TSkill) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TSkill) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
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
