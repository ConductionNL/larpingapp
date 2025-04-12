# Abstract Object Store

The Abstract Object Store provides a generic interface for working with any type of object in the LARPingApp. It uses the Settings Store to determine API endpoints based on register and schema slugs.

## Overview

This store offers a reusable way to interact with objects of any type through a consistent interface. It handles common operations like fetching, creating, updating, and deleting objects, as well as retrieving related data like audit trails, relations, and uses.

## State

The store maintains the following state:

- `objectItem`: The currently active object (or false if none is selected)
- `objectType`: The type of object currently being worked with (e.g., 'ability', 'character')
- `objectList`: Array of objects for the current type
- `auditTrails`: Audit trail entries for the current object
- `relations`: Relations for the current object
- `uses`: Uses of the current object
- `isLoadingObject`, `isLoadingObjectList`, etc.: Loading state flags
- `searchTerm`: Current search term for filtering objects
- `searchDebounceTimer`: Debounce timer for search operations

## Getters

The store provides the following computed properties:

- `objectSlugs`: Returns the register and schema slugs for the current object type
- `baseEndpoint`: Builds and returns the base API endpoint for the current object type

## Actions

The store provides the following actions:

### Basic Operations

- `setObjectType(objectType)`: Sets the current object type
- `setObjectItem(objectItem, objectType)`: Sets the active object and loads its audit trails and relations
- `setObjectList(objectList, objectType)`: Sets the list of objects

### Search Operations

- `setSearchTerm(term)`: Sets the search term and triggers a debounced search
- `clearSearch()`: Clears the search term and refreshes the list

### Data Loading

- `refreshObjectList(objectType, queryParams)`: Fetches and refreshes the list of objects
- `getObject(id, objectType, queryParams)`: Fetches a single object by ID

### Related Data Management

- `setAuditTrails(auditTrails)`: Sets the audit trails for the current object
- `setRelations(relations)`: Sets the relations for the current object
- `setUses(uses)`: Sets the uses for the current object
- `getAuditTrails(id)`: Fetches audit trails for an object
- `getRelations(id)`: Fetches relations for an object
- `getUses(id)`: Fetches uses for an object

### CRUD Operations

- `deleteObject()`: Deletes the current object
- `saveObject(objectItem, objectType)`: Creates or updates an object

## API Endpoints

The store interacts with the following endpoints, where register_slug and schema_slug are determined from the Settings Store:

- List objects: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]`
- Get object: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]/[id]`
- Create object: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]` (POST)
- Update object: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]/[id]` (PUT)
- Delete object: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]/[id]` (DELETE)
- Get audit trails: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]/[id]/audit`
- Get relations: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]/[id]/relations`
- Get uses: `/index.php/apps/openregister/api/objects/[register_slug]/[schema_slug]/[id]/uses`

## Usage Examples

### Working with different object types

```javascript
import { useObjectStore } from '../store/modules/object'

const objectStore = useObjectStore()

// Working with abilities
await objectStore.refreshObjectList('ability')
const abilities = objectStore.objectList

// Working with characters
await objectStore.refreshObjectList('character')
const characters = objectStore.objectList

// Get a specific item with additional parameters
const item = await objectStore.getObject('123abc', 'item', { _extend: 'properties,effects' })
```

### Creating and updating objects

```javascript
// Create a new object
const newObject = {
  name: 'New Character',
  description: 'A brave adventurer'
}
await objectStore.saveObject(newObject, 'character')

// Update an existing object
const updatedObject = {
  id: '123abc',
  name: 'Updated Character',
  description: 'Now even braver'
}
await objectStore.saveObject(updatedObject, 'character')
```

### Managing the current object

```javascript
// Set the current object and load its details
await objectStore.setObjectItem(someObject, 'location')

// Get additional data for the current object
await objectStore.getUses(objectStore.objectItem.id)
const uses = objectStore.uses

// Delete the current object
await objectStore.deleteObject()
```

### Searching objects

```javascript
// Set the object type
objectStore.setObjectType('event')

// Search for events
objectStore.setSearchTerm('tournament')
// The search will be debounced and trigger a refresh automatically

// Clear search
await objectStore.clearSearch()
```

## Error Handling

All methods that interact with the API include proper error handling. They validate that:

1. Object type is set before performing operations
2. Object item is available when needed
3. API responses are valid
4. Network errors are caught and reported

Errors are logged to the console and thrown to allow calling code to handle them appropriately. 