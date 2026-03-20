# Proposal: larping-skill-widget

## Summary

Add LarpingApp-specific dashboard widgets that visualize skill usage, character stats, effect chains, and skill dependencies using data from OpenRegister's GraphQL faceting API and the CnDashboardPage shared component.

## Motivation

LarpingApp has a rich domain model with characters, skills, abilities, effects, items, conditions, and events. Game masters need visual insights into skill distribution, character stat breakdowns, and effect chains to manage their LARP campaigns effectively. The existing dashboard infrastructure (CnDashboardPage) supports custom widgets, but LarpingApp currently only has basic KPI counters and a single skill usage chart.

## Scope

### In Scope

- Skill usage donut chart (partially implemented)
- Character stat breakdown panel with audit trail
- Multi-character comparison table and bar chart
- Skill dependency graph visualization
- Effect chain flow diagram
- Interactive skill assignment from widget
- Character sheet widget with PDF export
- NL Design System theming for all widgets
- Responsive layout across viewports

### Out of Scope

- New backend API endpoints (uses existing GraphQL and REST)
- Changes to CharacterService calculation logic
- New entity types or schema changes
