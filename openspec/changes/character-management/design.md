---
status: active
---

# Character Management Design

## Key Decisions

1. **Add CharacterService unit tests**: Test calculateCharacter(), initializeAbilityScores(), applyEffects(), and edge cases like null references and missing entities.
2. **Add CharactersController unit tests**: Test PDF download endpoint including DocuDesk unavailability and character not found scenarios.
3. **Test stat calculation with audit trail**: Verify the audit trail records old/new values correctly during effect application.
