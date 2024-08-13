import { Event } from './event'
import { TEvent } from './event.types'

export const mockEventData = (): TEvent[] => [
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

export const mockEvent = (data: TEvent[] = mockEventData()): TEvent[] => data.map(item => new Event(item))
