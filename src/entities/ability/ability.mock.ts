import { Ability } from './ability'
import { TAbility } from './ability.types'

export const mockAbilityData = (): TAbility[] => [
	{ // full data
		id: '1',
		name: 'ref1',
	},
	{
		id: '2',
		name: 'ref2',
	},
	{ // invalid data
		id: '3',
		name: 'ref3',
	},
]

export const mockAbility = (data: TAbility[] = mockAbilityData()): TAbility[] => data.map(item => new Ability(item))
