# RPG System

## Overview

The RPG System defines the core game mechanics of LarpingApp: Skills, Items, Conditions, Effects, and Abilities (stats). These entities form an interconnected system where Effects serve as the bridge between game elements and character stats.

## Current State

The RPG system pages are currently blocked by the OpenRegister availability check in the compiled frontend.

**Source routes:**
- `/#/abilities` -- Abilities (stats) list
- `/#/abilities/:id` -- Ability detail
- `/#/skills` -- Skills list
- `/#/skills/:id` -- Skill detail
- `/#/items` -- Items list
- `/#/items/:id` -- Item detail
- `/#/conditions` -- Conditions list
- `/#/conditions/:id` -- Condition detail
- `/#/effects` -- Effects list
- `/#/effects/:id` -- Effect detail

![RPG System - Abilities page](../screenshots/rpg-system.png)

## Features

### Ability (Stat) Management
- Create abilities with name, description, and base value
- Update and delete abilities with confirmation dialog
- List abilities with search and pagination
- View ability details with relations and audit trail tabs
- Base value defaults to 0 when not specified
- `allowed_negative` boolean field controls whether stat values can go below zero

### Skill Management
- Create skills with name, description, and effect text
- Skills carry arrays of effects that modify character abilities
- Prerequisite system: skills can require other skills, minimum stat values, conditions, or effects
- Skills can have a cost value (e.g., XP cost)

### Item Management
- Create items with name, description, and effect text
- Items carry arrays of effects applied to characters who carry them
- Items can be assigned to characters

### Condition Management
- Create conditions with name and description
- Conditions carry arrays of effects applied while active
- Conditions can be assigned to characters

### Effect Management
- Effects are the fundamental modifier unit in the system
- Each effect targets a specific ability with a modifier value
- Effects are collected from skills, items, conditions, and events during stat calculation

### Stat Calculation Flow

1. Start with each ability's base value
2. Collect effects from all character's skills, items, conditions, and events
3. Apply each effect's modifier to the targeted ability
4. Enforce `allowed_negative` constraint (clamp to 0 if not allowed)
5. Return computed stat block

## Technical Details

| Component | Path |
|-----------|------|
| Ability entity | `lib/Db/Ability.php` |
| Skill entity | `lib/Db/Skill.php` |
| Item entity | `lib/Db/Item.php` |
| Condition entity | `lib/Db/Condition.php` |
| Effect entity | `lib/Db/Effect.php` |
| Stat calculation | `lib/Service/CharacterService.php` |

## Related Specs

- [RPG System Spec](../../openspec/specs/rpg-system/spec.md)
