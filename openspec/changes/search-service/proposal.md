# Proposal: Search Service Dead Code Cleanup

## Problem
The search-service spec documents a SearchService.php that was dead code from OpenCatalogi.

## Solution
The SearchService.php has already been removed. This change documents the cleanup
and updates the spec status to reflect that dead code has been removed.
The actual search functionality is handled by the frontend object store (SRCH-090 through SRCH-094).
