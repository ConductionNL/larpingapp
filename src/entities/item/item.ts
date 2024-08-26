import { SafeParseReturnType, z } from 'zod'
import { TItem } from './item.types'

export class Item implements TItem {
	id: string
	name: string
	description?: string
	effect?: string
	effects?: string[] // Array of Effect UUIDs
	unique: boolean
	characters?: string[] // Array of Character UUIDs

	constructor(item: TItem) {
		this.id = item.id
		this.name = item.name
		this.description = item.description
		this.effect = item.effect
		this.effects = item.effects
		this.unique = item.unique ?? true
		this.characters = item.characters
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TItem) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TItem, unknown> {
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