import { Listing } from './effect'
import { TListing } from './effect.types'

export const mockListingsData = (): TListing[] => [
	{
		id: '1',
		catalogusId: '24',
		title: 'test 1',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		search: 'https://google.com',
		directory: 'https://google.com',
		metadata: ['string'],
		status: 'active',
		statusCode: 200,
		lastSync: '2024-07-25T00:00:00Z',
		default: true,
		available: false,
	},
	{
		id: '2',
		catalogusId: '24',
		title: 'test 2',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		search: '',
		directory: '',
		metadata: ['string'],
		status: 'active',
		statusCode: 200,
		lastSync: '',
		default: true,
		available: false,
	},
	{
		id: '1',
		catalogusId: '24',
		title: 'test 1',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		search: 'https://google.com',
		// directory is supposed to be an URL
		directory: 'string',
		// @ts-expect-error -- metadata is supposed to be a array, this is invalid for testing reasons
		metadata: 'string',
		status: 'active',
		// statusCode cannot be below 200
		statusCode: 199,
		lastSync: '2024-07-25T00:00:00Z',
		default: true,
		available: false,
	},
]

export const mockListings = (data: TListing[] = mockListingsData()): TListing[] => data.map(item => new Listing(item))
