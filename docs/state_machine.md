# Configuring a state machine

You can either use [Symfony workflow](https://symfony.com/doc/current/components/workflow.html) or [Winzou state machine](https://github.com/winzou/StateMachineBundle).
The recommended way is to use the `Symfony workflow component`.

## Configuring Symfony workflow as state machine`

If Symfony workflow is on your requirements, you have nothing to do even if winzou state machine is on your requirements too.

But you can configure it explicitly:

```yaml
sylius_resources:
    state_machine_component: symfony
```

## Configuring Winzou as state machine`

If Winzou state machine is on your requirements but Symfony workflow is not, you have nothing to do.

But you can configure it explicitly:

```yaml
sylius_resources:
    state_machine_component: winzou
```

## Applying a transition`

You can create a route to apply any transition

```yaml
app_pull_request_apply_transition:
    path: /pull-requests/{id}/{transition}
    methods: [PUT]
    defaults:
        _controller: app.controller.pull_request:applyStateMachineTransitionAction
        _sylius:
            csrf_protection: false
            state_machine:
                transition: $transition
```
