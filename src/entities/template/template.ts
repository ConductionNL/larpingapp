import { SafeParseReturnType, z } from 'zod'
import { TTemplate } from './template.types'

export class Template implements TTemplate {
	id?: string
	name: string
	description?: string
	template?: string

	constructor(template: TTemplate) {
		this.id = template.id
		this.name = template.name
		this.description = template.description
		this.template = template.template
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TTemplate) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TTemplate, unknown> {
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