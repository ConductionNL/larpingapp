import { SafeParseReturnType, z } from 'zod'
import { TCharacter } from './character.types'

export class Character implements TCharacter {

	public id: string
	public title: string
	public summary: string
	public description: string
	public image: string
	public listed: boolean
	public organisation: string

	public metadata: string[]

	constructor(data: TCharacter) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TCharacter) {
		this.id = data?.id?.toString() || ''
		this.title = data?.title || ''
		this.summary = data?.summary || ''
		this.description = data?.description || ''
		this.image = data?.image || ''
		this.listed = data?.listed || false
		this.organisation = data.organisation || ''
		this.metadata = (Array.isArray(data.metadata) && data.metadata) || []
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TCharacter, unknown> {
		// https://conduction.stoplight.io/docs/open-catalogi/l89lv7ocvq848-create-catalog
		const schema = z.object({
			title: z.string().min(1).max(255), // .min(1) on a string functionally works the same as a nonEmpty check (SHOULD NOT BE COMBINED WITH .OPTIONAL())
			summary: z.string().min(1).max(255),
			description: z.string().max(2555),
			image: z.string().max(255),
			listed: z.boolean(),
			organisation: z.string(),
			metadata: z.string().array(),
		})

		const result = schema.safeParse({
			...this,
		})

		return result
	}

}
