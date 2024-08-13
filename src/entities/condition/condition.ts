import { SafeParseReturnType, z } from 'zod'
import { TCondition } from './condition.types'

export class Condition implements TCondition {

	public useElastic: boolean
	public useMongo: boolean

	constructor(data: TCondition) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TCondition) {
		this.useElastic = data?.useElastic || false
		this.useMongo = data?.useMongo || false
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TCondition, unknown> {
		// https://conduction.stoplight.io/docs/open-catalogi/8azwyic71djee-create-listing
		const schema = z.object({
			useElastic: z.boolean(),
			useMongo: z.boolean(),
		})

		const result = schema.safeParse({
			...this,
		})

		return result
	}

}
