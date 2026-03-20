# Search Service (Deprecated)

## Overview

The `SearchService` (`lib/Service/SearchService.php`) contains federated search capabilities inherited from the OpenCatalogi codebase. This service is **largely dead/inherited code** that was carried over during the fork of LarpingApp from the OpenCatalogi project.

This is a **backend-only** component with no dedicated UI. No screenshot is applicable.

## Status: Deprecated / Dead Code

The `search()` method references services that do not exist in LarpingApp (`$this->elasticService`, `$this->directoryService`), making it non-functional. The utility methods for MongoDB/MySQL filter construction and query string parsing are standalone and functional, but are not called from anywhere in the LarpingApp codebase.

**The app uses `ObjectService.getResultArrayForRequest()` for all search operations instead.**

## Dead Code Components

### Federated Search (`search()`)
- Accepts parameters, elasticConfig, dbConfig, and optional catalogi array
- Would query local Elastic search index first (if configured)
- Would discover remote catalog instances via `directoryService.listDirectory()`
- Would execute async Guzzle GET requests to all remote search endpoints
- Would merge remote results sorted by `_score`
- **None of this works** -- dependent services do not exist

### Facet/Aggregation Merging
- Methods for merging facet aggregation results from multiple sources
- Standalone and functional but unused

### Filter Construction
- MongoDB filter construction methods
- MySQL/MariaDB filter construction methods
- Custom query string parser supporting nested bracket syntax
- All functional but unused in the LarpingApp context

## Technical Details

| Component | Path |
|-----------|------|
| Service | `lib/Service/SearchService.php` |
| Actual search | `lib/Service/ObjectService.php` (`getResultArrayForRequest()`) |

## Recommendation

This service should be considered for removal in a future cleanup pass. The only potentially reusable components are the query string parser and filter construction utilities, which could be extracted if needed.

## Related Specs

- [Search Service Spec](../../openspec/specs/search-service/spec.md)
