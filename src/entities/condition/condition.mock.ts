import { Condition } from './condition'
import { TCondition } from './condition.types'

export const mockConditionData = (): TCondition[] => [
	{
		id: '1',
		name: 'Decat',
	},
	{
		id: '2',
		name: 'Woo',
	},
	{
		id: '3',
		name: 'Foo',
	},
]

export const mockCondition = (data: TCondition[] = mockConditionData()): TCondition[] => data.map(item => new Condition(item))
