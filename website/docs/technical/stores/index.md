# Store Documentation

This section contains documentation for the various Pinia stores used in the LARPingApp.

## Available Stores

- [Ability Store](ability.md) - Manages ability objects
- [Character Store](character.md) - Manages character objects
- [Settings Store](settings.md) - Manages application settings like register and schema slugs
- [Object Store](object.md) - Abstract store that can work with any object type

## Store Architecture

The LARPingApp uses Pinia for state management. The application's state is divided into several domain-specific stores, each responsible for a specific area of the application.

### Entity-Specific Stores

Stores like the Ability Store and Character Store are designed to work with specific entity types. They provide specialized methods and state for handling these entities.

### Abstract Object Store

The Object Store provides a generic interface for working with any type of object. It uses the Settings Store to determine API endpoints based on register and schema slugs. This store is useful when working with multiple object types or when building generic components.

### Settings Store

The Settings Store manages application-wide settings, particularly register and schema slugs for each object type. These settings determine how the application communicates with the backend API.

## Store Patterns

All stores follow consistent patterns:

1. **State**: Each store defines its state using the `state()` function
2. **Getters**: Computed properties for derived state
3. **Actions**: Methods for changing state or interacting with the API
4. **Loading States**: Flags indicating loading status for various operations

## API Integration

Stores interact with the backend API using the fetch API. The endpoints follow this pattern:

- Entity-specific stores: `/index.php/apps/larpingapp/api/objects/[entity-type]`
- Abstract object store: `/index.php/apps/openregister/api/objects/[register-slug]/[schema-slug]`

## Usage Guidelines

- Use entity-specific stores when working with a single entity type
- Use the abstract object store when building generic components or working with multiple entity types
- Always handle loading states in the UI
- Handle API errors appropriately to provide feedback to the user 