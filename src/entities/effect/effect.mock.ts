import { Effect } from './effect'
import { TEffect } from './effect.types'

export const mockEffectData = (): TEffect[] => [
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

export const mockEffect = (data: TEffect[] = mockEffectData()): TEffect[] => data.map(item => new Effect(item))
