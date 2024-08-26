export type TEvent = {
    id?: string
    name: string
    description?: string
    characters?: string[] // Array of Character UUIDs
    effects?: string[] // Array of Effect UUIDs
    startDate?: string
    endDate?: string
    location?: string
}