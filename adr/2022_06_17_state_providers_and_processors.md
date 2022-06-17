# Using state providers and state processors to handle all actions for a resource

* Status: new
* Date: 2022-06-17

## Context and Problem Statement

The current actions are too coupled to a system that use: 
* forms for CRUD
* resource persistence

## Considered Options

If we want something that is fully customizable and working for every cases.
We could be inspired by the new ApiPlatform handling resource system which just use state providers and state processors.
Indeed, processing a resource is not necessary persisting it. This also allows to implement the DTO system.

* [State Providers](https://api-platform.com/docs/main/core/state-providers/)
* [State Processors](https://api-platform.com/docs/main/core/state-processors/)

Like Api Platform, we can use decorating system and decorating priorities for our built-in actions.

Proposal for built-in State Providers and Processors for each current actions:
* [Create Action](2022_06_17_state_providers_and_processors_for_create_action.md)
