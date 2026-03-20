# LarpingApp Feature Documentation

Documentation for all LarpingApp features, organized by spec area. Each feature page includes screenshots (where applicable), current state, feature descriptions, and technical details.

## Features

### User-Facing Features

| Feature | Description | Status |
|---------|-------------|--------|
| [Dashboard](features/dashboard.md) | Landing page with welcome view and planned analytics | Blocked by stale build |
| [Character Management](features/character-management.md) | Full CRUD for LARP characters with stat calculation | Blocked by stale build |
| [Game Mechanics](features/game-mechanics.md) | Skills, Items, Conditions, Effects, and Abilities | Blocked by stale build |
| [RPG System](features/rpg-system.md) | Core RPG mechanics and stat calculation engine | Blocked by stale build |
| [Events and Players](features/events-players.md) | Event management and player profiles | Blocked by stale build |
| [Admin Settings](features/admin-settings.md) | Per-object-type data source configuration | Bug: IIconSection vs ISettings |
| [PDF Export](features/pdf-export.md) | Character sheet PDF generation via DocuDesk | Requires DocuDesk |

### Backend Services

| Feature | Description | Status |
|---------|-------------|--------|
| [Object Service](features/object-service.md) | Central data access layer for all entity types | Implemented |
| [Search Service](features/search-service.md) | Federated search (inherited from OpenCatalogi) | Deprecated / Dead Code |

### Planned / Unspecified

| Feature | Description | Status |
|---------|-------------|--------|
| [Larping Skill Widget](features/larping-skill-widget.md) | Dashboard widget for character skills | No spec found |

## Screenshots

All screenshots are stored in the [screenshots/](screenshots/) directory.

| Screenshot | Feature | Notes |
|------------|---------|-------|
| [dashboard.png](screenshots/dashboard.png) | Dashboard | Shows "OpenRegister is required" empty state |
| [character-management.png](screenshots/character-management.png) | Character Management | Shows "OpenRegister is required" empty state |
| [game-mechanics.png](screenshots/game-mechanics.png) | Game Mechanics | Shows "OpenRegister is required" empty state |
| [rpg-system.png](screenshots/rpg-system.png) | RPG System | Shows "OpenRegister is required" empty state |
| [events-players.png](screenshots/events-players.png) | Events and Players | Shows "OpenRegister is required" empty state |
| [admin-settings.png](screenshots/admin-settings.png) | Admin Settings | Shows Nextcloud admin panel (no Larping section) |

## Known Issues

1. **Stale compiled JS**: The compiled JavaScript (`js/larpingapp-main.js`) includes an OpenRegister availability check (`hasOpenRegisters`) that is not present in the current source code (`src/App.vue`). This causes all routes to display an "OpenRegister is required" empty state, even though OpenRegister IS installed and configured. Rebuilding the app from source would resolve this, but the build currently fails with 2585 errors.

2. **Admin settings bug**: The `lib/Settings/LarpingAppAdmin.php` class implements `IIconSection` instead of `ISettings`, preventing the admin settings panel from rendering. The section does not appear in the admin sidebar.

3. **Search service dead code**: The `SearchService` references services (`elasticService`, `directoryService`) that do not exist in LarpingApp. It should be removed or cleaned up.

## Architecture

LarpingApp is a Nextcloud app that provides LARP (Live Action Role Playing) game management. Key architectural points:

- **Data layer**: All data operations go through `ObjectService`, which dispatches to either internal QBMappers or OpenRegister mappers
- **Frontend**: Vue 2 SPA with vue-router, using `@conduction/nextcloud-vue` shared components
- **Entity system**: 10 entity types (ability, character, condition, effect, event, item, player, setting, skill, template)
- **Game engine**: Effects modify Abilities; Skills/Items/Conditions/Events carry Effects; CharacterService computes final stats
- **PDF export**: Delegated to DocuDesk app via DI container
