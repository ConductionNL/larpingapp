import { SafeParseReturnType, z } from 'zod'
import { TPlayer } from './player.types'

export class Player implements TPlayer {
	public id: string
	public name: string

	constructor(player: TPlayer) {
		this.id = player.id || ''
		this.name = player.name || ''
	}

	/* istanbul ignore next */
	private hydrate(data: TPlayer) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TPlayer, unknown> {
		const schema = z.object({
			name: z.string().min(1),
		})

		return schema.safeParse({ ...this })
	}
}
