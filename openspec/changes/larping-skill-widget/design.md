# Design: larping-skill-widget

## Architecture Overview

All widgets render inside CnDashboardPage from @conduction/nextcloud-vue using scoped slots. Data flows through the useObjectStore() Pinia store and OpenRegister's GraphQL API.

```
CnDashboardPage (grid container)
├── #widget-skill-usage    → SkillUsageChart (GraphQL faceting)
├── #widget-character-stats → CharacterStatWidget (calculateCharacter)
├── #widget-comparison      → ComparisonWidget (multi-character)
├── #widget-skill-deps      → SkillDependencyGraph (directed graph)
├── #widget-effect-chain    → EffectChainWidget (flow diagram)
└── #widget-character-sheet → CharacterSheetWidget (printable + PDF)
```

## Data Flow

1. Widget mounts → check OpenRegister configuration via useSettingsStore()
2. Fetch data via GraphQL faceting query or useObjectStore().fetchCollection()
3. Process response → transform into chart/table data
4. Render via VueApexCharts (charts) or custom Vue components (graphs, tables)

## Key Decisions

- Single GraphQL query per widget (faceting) instead of multiple REST calls
- CSS custom properties only for NL Design theming compatibility
- Effect processing order matches backend: skills → items → conditions → events
- Maximum 5 characters for comparison to keep UI manageable
