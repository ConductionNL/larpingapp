import { SafeParseReturnType, z } from 'zod'
import { TEvent } from './event.types'

export class Event implements TEvent {
	id?: string
	name: string
	description?: string
	characters?: string[] // Array of Character UUIDs
	effects?: string[] // Array of Effect UUIDs
	startDate?: string
	endDate?: string
	location?: string

	constructor(event: TEvent) {
		this.id = event.id
		this.name = event.name
		this.description = event.description
		this.characters = event.characters
		this.effects = event.effects
		this.startDate = event.startDate
		this.endDate = event.endDate
		this.location = event.location
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TEvent) {
		this.id = data?.id?.toString() || ''
		this.name = data?.name || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TEvent, unknown> {
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