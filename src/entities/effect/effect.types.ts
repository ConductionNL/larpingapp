export type TEffect = {
    id: string
    catalogusId: string
	title: string
	summary: string
	description: string
	search: string
	directory: string
	metadata: string[]
	status: string
	statusCode: number
	lastSync: string | Date
	available: boolean
	default: boolean
}
