import { SafeParseReturnType, z } from 'zod'
import { TPlayer } from './player.types'

export class Player implements TPlayer {

	public id: string
	public title: string
	public summary: string
	public description: string
	public image: string

	constructor(data: TPlayer) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TPlayer) {
		this.id = data?.id?.toString() || ''
		this.title = data?.title || ''
		this.summary = data?.summary || ''
		this.description = data?.description || ''
		this.image = data?.image || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TPlayer, unknown> {
		// https://conduction.stoplight.io/docs/open-catalogi/hpksgr0u1cwj8-theme
		const schema = z.object({
			title: z.string().min(1),
			summary: z.string().min(1),
			description: z.string(),
			image: z.string(),
		})

		const result = schema.safeParse({
			...this,
		})

		return result
	}

}
