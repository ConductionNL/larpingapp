import { Condition } from './condition'
import { TCondition } from './condition.types'

export const mockConfigurationData = (): TCondition[] => [
	{ // full data
		useElastic: true,
		useMongo: true,
	},
	// @ts-expect-error -- useMongo doesn't exist
	{ // partial data
		useElastic: true,
	},
	{ // invalid data
		// @ts-expect-error -- useElastic is supposed to be a boolean
		useElastic: 'string',
		useMongo: false,
	},
]

export const mockConfiguration = (data: TConfiguration[] = mockConfigurationData()): TConfiguration[] => data.map(item => new Configuration(item))
