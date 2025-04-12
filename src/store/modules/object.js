/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { useSettingsStore } from './settings.js'

/**
 * Abstract store for managing generic object data
 * @category Store
 * @package
 * @author Claude AI
 * @copyright 2023 LARPingApp
 * @license AGPL-3.0
 * @version 1.0.0
 * @link https://nextcloud.com/
 *
 * @phpstan-type ObjectData {id: string, name: string, [key: string]: any}
 */
export const useObjectStore = defineStore(
	'object', {
		state: () => ({
			/** @member {object | false} Current active object */
			objectItem: false,
			/** @member {string|null} Type of the current object */
			objectType: null,
			/** @member {Array<object>} List of objects */
			objectList: [],
			/** @member {Array<object>} Audit trail entries for current object */
			auditTrails: [],
			/** @member {Array<object>} Relations for current object */
			relations: [],
			/** @member {Array<object>} Uses of current object */
			uses: [],
			// Loading states
			/** @member {boolean} Whether object is being loaded */
			isLoadingObject: false,
			/** @member {boolean} Whether object list is being loaded */
			isLoadingObjectList: false,
			/** @member {boolean} Whether audit trails are being loaded */
			isLoadingAuditTrails: false,
			/** @member {boolean} Whether relations are being loaded */
			isLoadingRelations: false,
			/** @member {boolean} Whether uses are being loaded */
			isLoadingUses: false,
			/** @member {string} Current search term for objects */
			searchTerm: '',
			/** @member {number|null} Debounce timer for search */
			searchDebounceTimer: null,
		}),
		getters: {
			/**
			 * Gets the register and schema slugs for the current object type
			 * @param state
			 * @return {object} Object containing register and schema slugs
			 */
			objectSlugs: (state) => {
				const settingsStore = useSettingsStore()

				if (!state.objectType) {
					console.error('No object type set')
					return {
						registerSlug: null,
						schemaSlug: null,
					}
				}

				return {
					registerSlug: settingsStore.getRegisterSlug(state.objectType),
					schemaSlug: settingsStore.getSchemaSlug(state.objectType),
				}
			},

			/**
			 * Builds the base API endpoint for the current object type
			 * @param state
			 * @return {string|null} The base API endpoint or null if no object type is set
			 */
			baseEndpoint: (state) => {
				const { registerSlug, schemaSlug } = state.objectSlugs

				if (!registerSlug || !schemaSlug) {
					return null
				}

				return `/index.php/apps/openregister/api/objects/${registerSlug}/${schemaSlug}`
			},
		},
		actions: {
			/**
			 * Sets the current object type
			 * @param {string} objectType - The type of object to work with
			 * @return {void}
			 */
			setObjectType(objectType) {
				this.objectType = objectType
				console.log(`Object type set to ${objectType}`)
			},

			/**
			 * Sets the active object item and loads its audit trails and relations
			 * @param {ObjectData|null} objectItem - The object item to set, or null to clear
			 * @param {string|null} objectType - Optional object type to set
			 * @throws {Error} When loading object data fails
			 * @return {Promise<void>}
			 */
			async setObjectItem(objectItem, objectType = null) {
				this.isLoadingObject = true

				try {
					// Set object type if provided
					if (objectType) {
						this.setObjectType(objectType)
					}

					// Validate object type is set
					if (!this.objectType) {
						throw new Error('Object type must be set before setting object item')
					}

					// Set the object item
					this.objectItem = objectItem
					console.log('Active object item set to ' + (objectItem ? objectItem.id : 'none'))

					// If we have an object item, load its audit trails and relations
					if (this.objectItem && this.objectItem.id) {
						await Promise.all([
							this.getAuditTrails(this.objectItem.id),
							this.getRelations(this.objectItem.id),
						])
					}
				} catch (err) {
					console.error('Error loading object data:', err)
					throw err
				} finally {
					this.isLoadingObject = false
				}
			},

			/**
			 * Sets the list of objects
			 * @param {Array<ObjectData>} objectList - Array of object data
			 * @param {string|null} objectType - Optional object type to set
			 * @return {void}
			 */
			setObjectList(objectList, objectType = null) {
				// Set object type if provided
				if (objectType) {
					this.setObjectType(objectType)
				}

				// Validate object type is set
				if (!this.objectType) {
					console.error('Object type must be set before setting object list')
					return
				}

				this.objectList = objectList
				console.log(`Object list for ${this.objectType} set to ${objectList.length} items`)
			},

			/**
			 * Sets the search term and triggers a debounced search
			 * @param {string} term - The search term to set
			 * @return {void}
			 */
			setSearchTerm(term) {
				this.searchTerm = term

				if (this.searchDebounceTimer) {
					clearTimeout(this.searchDebounceTimer)
				}

				this.searchDebounceTimer = setTimeout(() => {
					this.refreshObjectList()
				}, 500)
			},

			/**
			 * Clears the search term and refreshes the list
			 * @return {Promise<void>}
			 */
			async clearSearch() {
				this.searchTerm = ''
				await this.refreshObjectList()
			},

			/**
			 * Fetches and refreshes the list of objects
			 * @param {string|null} objectType - Optional object type to set
			 * @param {object} queryParams - Additional query parameters
			 * @throws {Error} When fetching objects fails
			 * @return {Promise<void>}
			 */
			async refreshObjectList(objectType = null, queryParams = {}) {
				// Set object type if provided
				if (objectType) {
					this.setObjectType(objectType)
				}

				// Validate object type is set
				if (!this.objectType) {
					throw new Error('Object type must be set before refreshing object list')
				}

				// Ensure baseEndpoint is available
				if (!this.baseEndpoint) {
					throw new Error('Could not determine API endpoint for object type: ' + this.objectType)
				}

				this.isLoadingObjectList = true

				// Build endpoint with query parameters
				let endpoint = this.baseEndpoint

				// Add search term if present
				if (this.searchTerm) {
					queryParams._search = this.searchTerm
				}

				// Add query parameters
				if (Object.keys(queryParams).length > 0) {
					const params = new URLSearchParams()

					for (const [key, value] of Object.entries(queryParams)) {
						params.append(key, value)
					}

					endpoint += `?${params.toString()}`
				}

				try {
					const response = await fetch(endpoint, { method: 'GET' })

					if (!response.ok) {
						throw new Error(`API error: ${response.status} ${response.statusText}`)
					}

					const data = await response.json()
					this.setObjectList(data.results || [])
					return data
				} catch (err) {
					console.error(`Error fetching ${this.objectType} list:`, err)
					throw err
				} finally {
					this.isLoadingObjectList = false
				}
			},

			/**
			 * Fetches a single object by ID
			 * @param {string} id - The object ID to fetch
			 * @param {string|null} objectType - Optional object type to set
			 * @param {object} queryParams - Additional query parameters
			 * @throws {Error} When fetching object fails
			 * @return {Promise<ObjectData>}
			 */
			async getObject(id, objectType = null, queryParams = {}) {
				// Set object type if provided
				if (objectType) {
					this.setObjectType(objectType)
				}

				// Validate object type is set
				if (!this.objectType) {
					throw new Error('Object type must be set before getting object')
				}

				// Ensure baseEndpoint is available
				if (!this.baseEndpoint) {
					throw new Error('Could not determine API endpoint for object type: ' + this.objectType)
				}

				this.isLoadingObject = true

				// Build endpoint with ID and query parameters
				let endpoint = `${this.baseEndpoint}/${id}`

				// Add query parameters
				if (Object.keys(queryParams).length > 0) {
					const params = new URLSearchParams()

					for (const [key, value] of Object.entries(queryParams)) {
						params.append(key, value)
					}

					endpoint += `?${params.toString()}`
				}

				try {
					const response = await fetch(endpoint, { method: 'GET' })

					if (!response.ok) {
						throw new Error(`API error: ${response.status} ${response.statusText}`)
					}

					const data = await response.json()
					this.setObjectItem(data)
					return data
				} catch (err) {
					console.error(`Error fetching ${this.objectType}:`, err)
					throw err
				} finally {
					this.isLoadingObject = false
				}
			},

			/**
			 * Sets the audit trails for the current object
			 * @param {Array<object>} auditTrails - The audit trails to set
			 * @return {void}
			 */
			setAuditTrails(auditTrails) {
				this.auditTrails = auditTrails
				console.log('Audit trails set with ' + auditTrails.length + ' items')
			},

			/**
			 * Sets the relations for the current object
			 * @param {Array<object>} relations - The relations to set
			 * @return {void}
			 */
			setRelations(relations) {
				this.relations = relations
				console.log('Relations set with ' + relations.length + ' items')
			},

			/**
			 * Sets the uses for the current object
			 * @param {Array<object>} uses - The uses to set
			 * @return {void}
			 */
			setUses(uses) {
				this.uses = uses
				console.log('Uses set with ' + uses.length + ' items')
			},

			/**
			 * Fetches audit trails for an object
			 * @param {string} id - The object ID
			 * @throws {Error} When ID is missing or fetch fails
			 * @return {Promise<Array<object>>}
			 */
			async getAuditTrails(id) {
				if (!id) {
					throw new Error('ID required to fetch audit trails')
				}

				if (!this.objectType) {
					throw new Error('Object type must be set before getting audit trails')
				}

				if (!this.baseEndpoint) {
					throw new Error('Could not determine API endpoint for object type: ' + this.objectType)
				}

				this.isLoadingAuditTrails = true

				console.log(`Fetching audit trails for ${this.objectType}...`)
				const endpoint = `${this.baseEndpoint}/${id}/audit`

				try {
					const response = await fetch(endpoint, { method: 'GET' })

					if (!response.ok) {
						throw new Error(`API error: ${response.status} ${response.statusText}`)
					}

					const data = await response.json()
					this.setAuditTrails(data)
					return data
				} catch (err) {
					console.error('Error fetching audit trails:', err)
					throw err
				} finally {
					this.isLoadingAuditTrails = false
				}
			},

			/**
			 * Fetches relations for an object
			 * @param {string} id - The object ID
			 * @throws {Error} When ID is missing or fetch fails
			 * @return {Promise<Array<object>>}
			 */
			async getRelations(id) {
				if (!id) {
					throw new Error('ID required to fetch relations')
				}

				if (!this.objectType) {
					throw new Error('Object type must be set before getting relations')
				}

				if (!this.baseEndpoint) {
					throw new Error('Could not determine API endpoint for object type: ' + this.objectType)
				}

				this.isLoadingRelations = true

				console.log(`Fetching relations for ${this.objectType}...`)
				const endpoint = `${this.baseEndpoint}/${id}/relations`

				try {
					const response = await fetch(endpoint, { method: 'GET' })

					if (!response.ok) {
						throw new Error(`API error: ${response.status} ${response.statusText}`)
					}

					const data = await response.json()
					this.setRelations(data)
					return data
				} catch (err) {
					console.error('Error fetching relations:', err)
					throw err
				} finally {
					this.isLoadingRelations = false
				}
			},

			/**
			 * Fetches uses for an object
			 * @param {string} id - The object ID
			 * @throws {Error} When ID is missing or fetch fails
			 * @return {Promise<Array<object>>}
			 */
			async getUses(id) {
				if (!id) {
					throw new Error('ID required to fetch uses')
				}

				if (!this.objectType) {
					throw new Error('Object type must be set before getting uses')
				}

				if (!this.baseEndpoint) {
					throw new Error('Could not determine API endpoint for object type: ' + this.objectType)
				}

				this.isLoadingUses = true

				console.log(`Fetching uses for ${this.objectType}...`)
				const endpoint = `${this.baseEndpoint}/${id}/uses`

				try {
					const response = await fetch(endpoint, { method: 'GET' })

					if (!response.ok) {
						throw new Error(`API error: ${response.status} ${response.statusText}`)
					}

					const data = await response.json()
					this.setUses(data)
					return data
				} catch (err) {
					console.error('Error fetching uses:', err)
					throw err
				} finally {
					this.isLoadingUses = false
				}
			},

			/**
			 * Deletes the current object
			 * @throws {Error} When no object is set or deletion fails
			 * @return {Promise<void>}
			 */
			async deleteObject() {
				if (!this.objectItem || !this.objectItem.id) {
					throw new Error('No object item to delete')
				}

				if (!this.objectType) {
					throw new Error('Object type must be set before deleting object')
				}

				if (!this.baseEndpoint) {
					throw new Error('Could not determine API endpoint for object type: ' + this.objectType)
				}

				console.log(`Deleting ${this.objectType}...`)
				const endpoint = `${this.baseEndpoint}/${this.objectItem.id}`

				try {
					const response = await fetch(endpoint, { method: 'DELETE' })

					if (!response.ok) {
						throw new Error(`API error: ${response.status} ${response.statusText}`)
					}

					// Refresh the list and clear the current item
					await this.refreshObjectList()
					this.objectItem = false

					return true
				} catch (err) {
					console.error(`Error deleting ${this.objectType}:`, err)
					throw err
				}
			},

			/**
			 * Creates or updates an object
			 * @param {ObjectData} objectItem - The object data to save
			 * @param {string|null} objectType - Optional object type to set
			 * @throws {Error} When saving object fails
			 * @return {Promise<ObjectData>}
			 */
			async saveObject(objectItem, objectType = null) {
				if (!objectItem) {
					throw new Error('No object item to save')
				}

				// Set object type if provided
				if (objectType) {
					this.setObjectType(objectType)
				}

				// Validate object type is set
				if (!this.objectType) {
					throw new Error('Object type must be set before saving object')
				}

				// Ensure baseEndpoint is available
				if (!this.baseEndpoint) {
					throw new Error('Could not determine API endpoint for object type: ' + this.objectType)
				}

				console.log(`Saving ${this.objectType}...`)

				const isNewObject = !objectItem.id
				const endpoint = isNewObject
					? this.baseEndpoint
					: `${this.baseEndpoint}/${objectItem.id}`
				const method = isNewObject ? 'POST' : 'PUT'

				// Create a copy of the object to avoid modifying the original
				const objectToSave = { ...objectItem }

				// Remove empty values from the object
				Object.keys(objectToSave).forEach(key => {
					if (objectToSave[key] === ''
						|| (Array.isArray(objectToSave[key]) && objectToSave[key].length === 0)) {
						delete objectToSave[key]
					}
				})

				try {
					const response = await fetch(
						endpoint,
						{
							method,
							headers: {
								'Content-Type': 'application/json',
							},
							body: JSON.stringify(objectToSave),
						},
					)

					if (!response.ok) {
						throw new Error(`API error: ${response.status} ${response.statusText}`)
					}

					const data = await response.json()
					await this.setObjectItem(data)
					console.log(`${this.objectType} saved`)

					// Refresh the list to include the new/updated object
					await this.refreshObjectList()

					return data
				} catch (err) {
					console.error(`Error saving ${this.objectType}:`, err)
					throw err
				}
			},
		},
	},
)
