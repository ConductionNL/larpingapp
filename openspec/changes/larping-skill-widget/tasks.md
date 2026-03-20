# Tasks: larping-skill-widget

## Task 1: Skill usage chart refinement
- **Spec ref**: specs/larping-skill-widget/spec.md#skill-usage-pie-chart
- **Status**: todo
- **Files**: src/views/dashboard/SkillUsageChart.vue
- **Acceptance criteria**:
  - GIVEN 5+ characters with skills WHEN dashboard loads THEN donut chart shows skill distribution
  - GIVEN >10 distinct skills WHEN chart renders THEN top 10 shown, rest grouped as "Other"

## Task 2: Character stat breakdown widget
- **Spec ref**: specs/larping-skill-widget/spec.md#character-stat-breakdown
- **Status**: todo
- **Files**: src/views/dashboard/CharacterStatWidget.vue (new)
- **Acceptance criteria**:
  - GIVEN character with skills/items WHEN selected THEN ability scores with base, modifiers, and final shown
  - GIVEN ability row clicked WHEN audit trail exists THEN expandable audit timeline displayed

## Task 3: Multi-character comparison
- **Spec ref**: specs/larping-skill-widget/spec.md#multi-character-comparison
- **Status**: todo
- **Files**: src/views/dashboard/ComparisonWidget.vue (new)
- **Acceptance criteria**:
  - GIVEN 2+ characters selected WHEN comparison renders THEN table and bar chart show side-by-side scores
  - GIVEN >5 characters selected WHEN 6th added THEN rejected with message

## Task 4: Skill dependency graph
- **Spec ref**: specs/larping-skill-widget/spec.md#skill-dependency-graph
- **Status**: todo
- **Files**: src/views/dashboard/SkillDependencyGraph.vue (new)
- **Acceptance criteria**:
  - GIVEN skills with prerequisites WHEN widget renders THEN directed graph with edges shown
  - GIVEN character selected WHEN graph renders THEN acquired skills highlighted

## Task 5: Effect chain visualization
- **Spec ref**: specs/larping-skill-widget/spec.md#effect-chain-visualization
- **Status**: todo
- **Files**: src/views/dashboard/EffectChainWidget.vue (new)
- **Acceptance criteria**:
  - GIVEN character with effects WHEN widget renders THEN flow diagram shows source→effect→ability chain

## Task 6: Interactive skill assignment
- **Spec ref**: specs/larping-skill-widget/spec.md#interactive-skill-selection
- **Status**: todo
- **Files**: src/views/dashboard/CharacterStatWidget.vue
- **Acceptance criteria**:
  - GIVEN character displayed WHEN "Add Skill" clicked and skill selected THEN skill added and scores recalculated

## Task 7: Character sheet widget with PDF export
- **Spec ref**: specs/larping-skill-widget/spec.md#printable-summary
- **Status**: todo
- **Files**: src/views/dashboard/CharacterSheetWidget.vue (new)
- **Acceptance criteria**:
  - GIVEN character selected WHEN widget renders THEN all entity sections displayed
  - GIVEN DocuDesk installed WHEN "Export PDF" clicked THEN PDF downloads

## Task 8: NL Design theming and responsive layout
- **Spec ref**: specs/larping-skill-widget/spec.md#nl-design-theming
- **Status**: todo
- **Files**: src/views/dashboard/*.vue
- **Acceptance criteria**:
  - GIVEN custom NL Design theme WHEN widgets render THEN all colors use CSS custom properties
  - GIVEN 360px viewport WHEN dashboard renders THEN widgets stack vertically
