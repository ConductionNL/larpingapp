// TODO: double check this type for correct properties and optionals when stoplight updates - https://conduction.stoplight.io/docs/open-catalogi/fee989a9c8e3f-publication

export type TPlayer = {
    id: string
	title: string
	summary: string
	description: string
	reference: string
	image: string
	category: string
	portal: string
	featured: boolean
    schema: string
    status: 'Concept' | 'Published' | 'Withdrawn' | 'Archived' | 'revised' | 'Rejected'
    attachments: TAttachment[]
    attachmentCount: number
    themes: string[]
    data: Record<string, any>
    anonymization: {
        anonymized: boolean
        results: string
    }
    language: {
        code: string
        level: string
    }
    published: string | Date
    modified: string | Date
    license: string
    archive: {
        date: string | Date
    }
    geo: {
        type: 'Point' | 'LineString' | 'Polygon' | 'MultiPoint' | 'MultiLineString' | 'MultiPolygon'
        coordinates: [number, number]
    }
    catalogi: string
    metaData: string
}
