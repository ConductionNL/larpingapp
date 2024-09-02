/* eslint-disable no-console */
import { setActivePinia, createPinia } from 'pinia'

import { usePublicationStore } from './publication.js'
import { Attachment, mockAttachments, mockPublications, Publication } from '../../entities/index.js'

describe(
	'Metadata Store', () => {
		beforeEach(
			() => {
				setActivePinia(createPinia())
			},
		)

		it(
			'sets publication item correctly', () => {
				const store = usePublicationStore()

				store.setPublicationItem(mockPublications()[0])

				expect(store.publicationItem).toBeInstanceOf(Publication)
				expect(store.publicationItem).toEqual(mockPublications()[0])
				expect(store.publicationItem.validate().success).toBe(true)

				store.setPublicationItem(mockPublications()[1])

				expect(store.publicationItem).toBeInstanceOf(Publication)
				expect(store.publicationItem).toEqual(mockPublications()[1])
				expect(store.publicationItem.validate().success).toBe(true)

				store.setPublicationItem(mockPublications()[2])

				expect(store.publicationItem).toBeInstanceOf(Publication)
				expect(store.publicationItem).toEqual(mockPublications()[2])
				expect(store.publicationItem.validate().success).toBe(false)
			})

		it(
			'sets publication list correctly', () => {
				const store = usePublicationStore()

				store.setPublicationList(mockPublications())

				expect(store.publicationList).toHaveLength(mockPublications().length)

				expect(store.publicationList[0]).toBeInstanceOf(Publication)
				expect(store.publicationList[0]).toEqual(mockPublications()[0])
				expect(store.publicationList[0].validate().success).toBe(true)

				expect(store.publicationList[1]).toBeInstanceOf(Publication)
				expect(store.publicationList[1]).toEqual(mockPublications()[1])
				expect(store.publicationList[1].validate().success).toBe(true)

				expect(store.publicationList[2]).toBeInstanceOf(Publication)
				expect(store.publicationList[2]).toEqual(mockPublications()[2])
				expect(store.publicationList[2].validate().success).toBe(false)
			})

		// TODO: fix this
		it(
			'set publication data.data property key correctly', () => {
				const store = usePublicationStore()

				store.setPublicationDataKey('contactPoint')

				expect(store.publicationDataKey).toBe('contactPoint')
			},
		)

		it(
			'set attachment item correctly', () => {
				const store = usePublicationStore()

				store.setAttachmentItem(mockAttachments()[0])

				expect(store.attachmentItem).toBeInstanceOf(Attachment)
				expect(store.attachmentItem).toEqual(mockAttachments()[0])
				expect(store.attachmentItem.validate().success).toBe(true)

				store.setAttachmentItem(mockAttachments()[1])

				expect(store.attachmentItem).toBeInstanceOf(Attachment)
				expect(store.attachmentItem).toEqual(mockAttachments()[1])
				expect(store.attachmentItem.validate().success).toBe(true)

				store.setAttachmentItem(mockAttachments()[2])

				expect(store.attachmentItem).toBeInstanceOf(Attachment)
				expect(store.attachmentItem).toEqual(mockAttachments()[2])
				expect(store.attachmentItem.validate().success).toBe(false)
			})
	})
