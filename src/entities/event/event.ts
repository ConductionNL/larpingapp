import { SafeParseReturnType, z } from 'zod'
import { TEvent } from './event.types'

export class Event implements TEvent {

	public id: string
	public title: string
	public summary: string
	public description: string
	public oin: string
	public tooi: string
	public rsin: string
	public pki: string

	constructor(data: TEvent) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TEvent) {
		this.id = data?.id?.toString() || ''
		this.title = data?.title || ''
		this.summary = data?.summary || ''
		this.description = data?.description || ''
		this.oin = data?.oin || ''
		this.tooi = data?.tooi || ''
		this.rsin = data?.rsin || ''
		this.pki = data?.pki || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TEvent, unknown> {
		// https://conduction.stoplight.io/docs/open-catalogi/ewlydzkylhygj-create-organisation
		const schema = z.object({
			title: z.string().min(1),
			summary: z.string().min(1),
			description: z.string(),
			oin: z.string(),
			tooi: z.string(),
			rsin: z.string(),
			pki: z.string(),
		})

		const result = schema.safeParse({
			...this,
		})

		return result
	}

}
