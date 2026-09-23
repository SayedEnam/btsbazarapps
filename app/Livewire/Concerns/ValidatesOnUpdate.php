<?php

namespace App\Livewire\Concerns;

use Livewire\Features\SupportFormObjects\Form;

trait ValidatesOnUpdate
{
    /**
     * Validate a single field the moment the user leaves it (paired with
     * wire:model.blur in the view), so a mistake is caught — and, just as
     * importantly, un-caught the moment it's fixed — without waiting for a
     * full form submit.
     *
     * Two cases, both handled by the same hook:
     *  - A nested Form object property ("form.name", "passwordForm.password",
     *    ...). validateOnly() already knows to delegate to that Form's own
     *    rules() once it sees the prefix is a Form instance — this mirrors
     *    that exact check so it works for a Form property under any name,
     *    not just one hardcoded property called "form".
     *  - A component's own top-level property, validated by a rules()
     *    method on the component itself (no nested Form object). hasRuleFor()
     *    guards against reacting to unrelated property changes (search
     *    boxes, pagination, modal-open flags, ...) that have no rule and
     *    would otherwise be a no-op query or a bad property lookup.
     */
    public function updated(string $property): void
    {
        $formPropertyName = str($property)->before('.')->toString();

        $isFormObjectPath = str_contains($property, '.')
            && property_exists($this, $formPropertyName)
            && ($this->{$formPropertyName} ?? null) instanceof Form;

        if ($isFormObjectPath) {
            $this->validateOnly($property);

            return;
        }

        if ($this->hasRuleFor($property)) {
            $this->validateOnly($property);
        }
    }
}
