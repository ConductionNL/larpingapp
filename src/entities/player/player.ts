import { SafeParseReturnType, z } from 'zod'
import { TPlayer } from './player.types'

export class Player implements TPlayer {

	public id: string
	public name: string

	constructor(data: TPlayer) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TPlayer) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TPlayer, unknown> {
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