import { Publication } from './player'
import { TPublication } from './player.types'

export const mockPublicationsData = (): TPublication[] => [
	{ // full data
		id: '1',
		reference: 'ref1',
		title: 'test 1',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		image: 'https://example.com/image.jpg',
		category: 'category1',
		portal: 'https://google.com',
		catalogi: 'catalogi1',
		metaData: 'meta1',
		published: '2024-01-01',
		modified: '2024-01-02',
		featured: true,
		data: {
			key: 'anyvalue',
			streetNumber: 1,
			object: {
                blabla: 'bla'
            },
            array: ['appel', 'peer', 0, [], {}]
		},
		attachments: [],
		attachmentCount: 1,
		schema: 'https://schema.org',
		status: 'Concept',
		license: 'MIT',
		themes: ['theme1'],
		anonymization: {
			anonymized: true,
			results: '',
		},
		language: {
			code: 'en-us',
			level: 'A1',
		},
		archive: {
			date: new Date(2023, 2, 24).toISOString(),
		},
		geo: {
			type: 'Point',
			coordinates: [2, 23],
		},
	},
	{ // partial data
		id: '2',
		reference: 'ref2',
		title: 'test 2',
		summary: 'a short form summary',
		description: '',
		image: '',
		category: 'category2',
		portal: '',
		catalogi: '',
		metaData: '',
		published: '2024-01-01',
		modified: '2024-01-02',
		featured: true,
		data: {
			type: '',
		},
		attachments: [],
		attachmentCount: 1,
		schema: 'https://schema.org',
		status: 'Concept',
		license: 'MIT',
		themes: ['theme1'],
		anonymization: {
			anonymized: true,
			results: '',
		},
		language: {
			code: 'en-us',
			level: 'A1',
		},
		archive: {
			date: new Date(2023, 2, 24).toISOString(),
		},
		geo: {
			type: 'Point',
			coordinates: [2, 23],
		},
	},
	{ // invalid data
		id: '3',
		reference: 'ref3',
		title: 'test 3',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		image: 'https://example.com/image.jpg',
		category: 'category3',
		portal: 'https://google.com',
		catalogi: 'catalogi3',
		metaData: 'meta1',
		published: '2024-01-01',
		modified: '2024-01-02',
		featured: true,
		data: {
			type: '',
		},
		attachments: [],
		attachmentCount: 1,
		schema: 'https://schema.org',
		// @ts-expect-error -- invalid data for testing
		status: true,
		license: 'MIT',
		themes: ['theme1'],
		anonymization: {
			anonymized: true,
			results: '',
		},
		language: {
			code: 'en-us',
			level: 'A1',
		},
		archive: {
			date: new Date(2023, 2, 24).toISOString(),
		},
		geo: {
			type: 'Point',
			coordinates: [2, 23],
		},
	},
]

export const mockPublications = (data: TPublication[] = mockPublicationsData()): TPublication[] => data.map(item => new Publication(item))
