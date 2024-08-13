import { Character } from './character'
import { TCharacter } from './character.types'

export const mockCharacterData = (): TCharacter[] => [
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

export const mockCharacter = (data: TCharacter[] = mockCharacterData()): TCharacter[] => data.map(item => new Character(item))
