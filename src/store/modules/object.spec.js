/* eslint-disable no-console */
import { setActivePinia, createPinia } from 'pinia'
import { useObjectStore } from './object.js'
import { useSettingsStore } from './settings.js'

// Mock fetch globally
global.fetch = jest.fn()

// Helper to create mock responses
const mockResponse = (status, data) => {
	return Promise.resolve({
		ok: status >= 200 && status < 300,
		status,
		statusText: status >= 200 && status < 300 ? 'OK' : 'Error',
		json: () => Promise.resolve(data)
	})
}

describe(
	'Object Store', () => {
		beforeEach(() => {
			setActivePinia(createPinia())
			jest.clearAllMocks()
			
			// Default fetch mock to return empty results
			global.fetch.mockImplementation(() => 
				mockResponse(200, { results: [] })
			)
		})
		
		it('sets object type correctly', () => {
			const store = useObjectStore()
			store.setObjectType('ability')
			
			expect(store.objectType).toBe('ability')
		})
		
		it('builds correct API endpoint using settings store', () => {
			const store = useObjectStore()
			const settingsStore = useSettingsStore()
			
			// Set custom register slug for ability
			settingsStore.updateRegisterSlugs({
				ability: 'custom-register'
			})
			
			// Set object type to ability
			store.setObjectType('ability')
			
			// Check the computed endpoint
			expect(store.baseEndpoint).toBe('/index.php/apps/openregister/api/objects/custom-register/ability')
		})
		
		it('sets object item correctly', async () => {
			const store = useObjectStore()
			
			// Mock audit trails and relations API calls
			global.fetch.mockImplementation((url) => {
				if (url.includes('/audit')) {
					return mockResponse(200, [{ id: 'audit1' }])
				} else if (url.includes('/relations')) {
					return mockResponse(200, [{ id: 'relation1' }])
				}
				return mockResponse(200, {})
			})
			
			const mockObject = { id: '123', name: 'Test Object' }
			await store.setObjectItem(mockObject, 'ability')
			
			expect(store.objectType).toBe('ability')
			expect(store.objectItem).toEqual(mockObject)
			expect(store.auditTrails).toEqual([{ id: 'audit1' }])
			expect(store.relations).toEqual([{ id: 'relation1' }])
			
			// Verify API calls were made correctly
			expect(global.fetch).toHaveBeenCalledTimes(2)
			expect(global.fetch).toHaveBeenCalledWith(
				'/index.php/apps/openregister/api/objects/larping/ability/123/audit', 
				{ method: 'GET' }
			)
			expect(global.fetch).toHaveBeenCalledWith(
				'/index.php/apps/openregister/api/objects/larping/ability/123/relations', 
				{ method: 'GET' }
			)
		})
		
		it('sets object list correctly', () => {
			const store = useObjectStore()
			const mockObjects = [
				{ id: '123', name: 'Object 1' },
				{ id: '456', name: 'Object 2' }
			]
			
			store.setObjectList(mockObjects, 'character')
			
			expect(store.objectType).toBe('character')
			expect(store.objectList).toEqual(mockObjects)
		})
		
		it('handles search and refreshes object list', async () => {
			const store = useObjectStore()
			store.setObjectType('item')
			
			// Mock the API response
			const mockItems = [
				{ id: '123', name: 'Sword' },
				{ id: '456', name: 'Shield' }
			]
			global.fetch.mockImplementation(() => 
				mockResponse(200, { results: mockItems })
			)
			
			// Set search term and manually call refresh (bypass debounce)
			store.setSearchTerm('sword')
			await store.refreshObjectList()
			
			// Verify the API call used the search term
			expect(global.fetch).toHaveBeenCalledTimes(1)
			expect(global.fetch.mock.calls[0][0]).toContain('_search=sword')
			expect(store.objectList).toEqual(mockItems)
		})
		
		it('fetches a single object correctly', async () => {
			const store = useObjectStore()
			const mockObject = { id: '123', name: 'Test Object' }
			
			// Mock the API response
			global.fetch.mockImplementation(() => 
				mockResponse(200, mockObject)
			)
			
			// Get the object
			const result = await store.getObject('123', 'location', { _extend: 'events' })
			
			// Verify the API call
			expect(global.fetch).toHaveBeenCalledTimes(1)
			expect(global.fetch.mock.calls[0][0]).toContain('/index.php/apps/openregister/api/objects/larping/location/123')
			expect(global.fetch.mock.calls[0][0]).toContain('_extend=events')
			
			// Check the result
			expect(result).toEqual(mockObject)
			expect(store.objectItem).toEqual(mockObject)
		})
		
		it('handles error when fetching object', async () => {
			const store = useObjectStore()
			
			// Mock API error
			global.fetch.mockImplementation(() => 
				mockResponse(404, { error: 'Not found' })
			)
			
			// Attempt to get the object
			await expect(store.getObject('123', 'player'))
				.rejects.toThrow('API error: 404 Error')
		})
		
		it('saves object correctly', async () => {
			const store = useObjectStore()
			const mockObject = { id: '123', name: 'Test Object', description: '' }
			const savedObject = { id: '123', name: 'Test Object' }
			
			// Mock the API responses
			global.fetch.mockImplementation((url, options) => {
				if (options.method === 'PUT') {
					// Verify empty values were removed
					const body = JSON.parse(options.body)
					expect(body).not.toHaveProperty('description')
					return mockResponse(200, savedObject)
				}
				return mockResponse(200, { results: [savedObject] })
			})
			
			// Save the object
			await store.saveObject(mockObject, 'scenario')
			
			// Verify the API calls
			expect(global.fetch).toHaveBeenCalledTimes(2) // Save + refresh list
			expect(global.fetch.mock.calls[0][0]).toBe('/index.php/apps/openregister/api/objects/larping/scenario/123')
			expect(global.fetch.mock.calls[0][1].method).toBe('PUT')
			
			// Verify state was updated
			expect(store.objectItem).toEqual(savedObject)
		})
		
		it('creates new object correctly', async () => {
			const store = useObjectStore()
			const newObject = { name: 'New Object' }
			const createdObject = { id: '789', name: 'New Object' }
			
			// Mock the API responses
			global.fetch.mockImplementation((url, options) => {
				if (options.method === 'POST') {
					return mockResponse(200, createdObject)
				}
				return mockResponse(200, { results: [createdObject] })
			})
			
			// Create the object
			await store.saveObject(newObject, 'organization')
			
			// Verify the API calls
			expect(global.fetch).toHaveBeenCalledTimes(2) // Create + refresh list
			expect(global.fetch.mock.calls[0][0]).toBe('/index.php/apps/openregister/api/objects/larping/organization')
			expect(global.fetch.mock.calls[0][1].method).toBe('POST')
			
			// Verify state was updated
			expect(store.objectItem).toEqual(createdObject)
		})
		
		it('deletes object correctly', async () => {
			const store = useObjectStore()
			const objectToDelete = { id: '123', name: 'Delete Me' }
			
			// Set the current object
			store.setObjectType('event')
			store.objectItem = objectToDelete
			
			// Mock the API responses
			global.fetch.mockImplementation((url, options) => {
				if (options.method === 'DELETE') {
					return mockResponse(200, { success: true })
				}
				return mockResponse(200, { results: [] })
			})
			
			// Delete the object
			await store.deleteObject()
			
			// Verify the API calls
			expect(global.fetch).toHaveBeenCalledTimes(2) // Delete + refresh list
			expect(global.fetch.mock.calls[0][0]).toBe('/index.php/apps/openregister/api/objects/larping/event/123')
			expect(global.fetch.mock.calls[0][1].method).toBe('DELETE')
			
			// Verify state was updated
			expect(store.objectItem).toBe(false)
		})
		
		it('fetches additional data correctly', async () => {
			const store = useObjectStore()
			
			// Set up the object
			store.setObjectType('ability')
			store.objectItem = { id: '123', name: 'Test Ability' }
			
			// Mock the API responses
			global.fetch.mockImplementation((url) => {
				if (url.includes('/uses')) {
					return mockResponse(200, [{ id: 'use1' }, { id: 'use2' }])
				}
				return mockResponse(200, [])
			})
			
			// Fetch uses
			await store.getUses('123')
			
			// Verify the API call
			expect(global.fetch).toHaveBeenCalledWith(
				'/index.php/apps/openregister/api/objects/larping/ability/123/uses', 
				{ method: 'GET' }
			)
			
			// Verify state was updated
			expect(store.uses).toEqual([{ id: 'use1' }, { id: 'use2' }])
		})
		
		it('validates object type is set before operations', async () => {
			const store = useObjectStore()
			
			// Test without setting object type
			await expect(store.refreshObjectList())
				.rejects.toThrow('Object type must be set before refreshing object list')
				
			await expect(store.getObject('123'))
				.rejects.toThrow('Object type must be set before getting object')
				
			await expect(store.saveObject({ name: 'Test' }))
				.rejects.toThrow('Object type must be set before saving object')
				
			// Set object item without setting type
			await expect(store.setObjectItem({ id: '123' }))
				.rejects.toThrow('Object type must be set before setting object item')
		})
	},
) 