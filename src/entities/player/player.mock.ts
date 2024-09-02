import { Player } from './player'
import { TPlayer } from './player.types'

export const mockPlayerData = (): TPlayer[] => [
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

export const mockPlayer = (data: TPlayer[] = mockPlayerData()): TPlayer[] => data.map(item => new Player(item))
