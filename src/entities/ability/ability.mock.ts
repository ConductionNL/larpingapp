import { Ability } from './ability'
import { TAbility } from './ability.types'

export const mockAttachmentsData = (): TAbility[] => [
	{ // full data
		id: '1',
		reference: 'ref1',
		title: 'test 1',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		labels: ['label1'],
		accessUrl: 'https://example.com/access',
		downloadUrl: 'https://example.com/download',
		type: 'document',
		extension: 'pdf',
		size: '1024',
		anonymization: {
			anonymized: true,
			results: 'success',
		},
		language: { code: 'en-us', level: 'C1' },
		versionOf: 'v1.0',
		hash: 'abc123',
		published: new Date(2022, 9, 14).toISOString(),
		modified: new Date(2022, 11, 2).toISOString(),
		license: 'MIT',
	},
	{
		id: '2',
		reference: 'ref2',
		title: 'test 2',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		labels: [],
		accessUrl: '',
		downloadUrl: '',
		type: 'document',
		extension: 'pdf',
		size: '1024',
		anonymization: {
			anonymized: true,
			results: 'success',
		},
		language: { code: '', level: '' },
		versionOf: 'v1.0',
		hash: 'hash',
		published: '',
		modified: '',
		license: 'MIT',
	},
	{ // invalid data
		id: '3',
		reference: 'ref3',
		title: 'test 3',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		labels: ['label3'],
		// this is supposed to be a URL
		accessUrl: 'non url',
		downloadUrl: 'https://example.com/download',
		type: 'document',
		extension: 'pdf',
		size: '1024',
		anonymization: {
			anonymized: true,
			results: 'success',
		},
		// invalid code and level
		language: { code: 'foo bar', level: 'funny' },
		versionOf: 'v1.0',
		hash: 'abc123',
		published: new Date(2022, 9, 14).toISOString(),
		modified: new Date(2022, 11, 2).toISOString(),
		license: 'MIT',
	},
]

export const mockAttachments = (data: TAttachment[] = mockAttachmentsData()): TAttachment[] => data.map(item => new Attachment(item))
