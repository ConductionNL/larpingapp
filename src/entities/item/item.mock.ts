import { Item } from './item'
import { TItem } from './item.types'

export const mockItemData = (): TItem[] => [
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

export const mockItem = (data: TItem[] = mockItemData()): TItem[] => data.map(item => new Item(item))
