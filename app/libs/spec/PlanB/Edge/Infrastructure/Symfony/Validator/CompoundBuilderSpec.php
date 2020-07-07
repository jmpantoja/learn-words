<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Validator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Validator\CompoundBuilder;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\Exception\NonExistentFieldException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;

class CompoundBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CompoundBuilder::class);
        $this->shouldHaveType(ConstraintBuilderInterface::class);
    }

    public function it_option_allow_extra_fields_is_true_by_default()
    {
        $this->toArray()->shouldHaveKeyWithValue('allowExtraFields', true);
    }

    public function it_is_able_to_set_allow_extra_fields()
    {
        $this->allowExtraFields();
        $this->toArray()->shouldHaveKeyWithValue('allowExtraFields', true);
    }

    public function it_option_allow_missing_fields_is_false_by_default()
    {
        $this->toArray()->shouldHaveKeyWithValue('allowMissingFields', false);
    }

    public function it_is_able_to_set_allow_missing_fields()
    {
        $this->allowMissingFields();
        $this->toArray()->shouldHaveKeyWithValue('allowMissingFields', true);
    }

    public function it_has_extra_fields_message_by_default()
    {
        $this->toArray()->shouldHaveKeyWithValue('extraFieldsMessage', 'This field was not expected.');
    }

    public function it_is_able_to_set_extra_fields_message()
    {
        $this->extraFieldsMessage('nuevo mensaje');
        $this->toArray()->shouldHaveKeyWithValue('extraFieldsMessage', 'nuevo mensaje');
    }

    public function it_has_missing_fields_message_by_default()
    {
        $this->toArray()->shouldHaveKeyWithValue('missingFieldsMessage', 'This field is missing.');
    }

    public function it_is_able_to_set_missing_fields_message()
    {
        $this->missingFieldsMessage('nuevo mensaje');
        $this->toArray()->shouldHaveKeyWithValue('missingFieldsMessage', 'nuevo mensaje');
    }

    public function it_has_empty_fields_option_by_default()
    {
        $this->toArray()->shouldHaveKeyWithValue('fields', []);
    }

    public function it_is_able_to_add_a_new_required_field(Constraint $first, Constraint $second)
    {
        $this->required('field', [
            $first,
            $second
        ]);

        $this->get('field')->shouldBeAnInstanceOf(Required::class);
        $this->get('field')->constraints->shouldBeEqualTo([
            $first,
            $second
        ]);
    }

    public function it_is_able_to_add_a_new_optional_field(Constraint $first, Constraint $second)
    {
        $this->optional('field', [
            $first,
            $second
        ]);

        $field = $this->get('field');

        $field->shouldBeAnInstanceOf(Optional::class);
        $field->constraints->shouldBeEqualTo([
            $first,
            $second
        ]);
    }

    public function it_throws_an_exception_when_an_non_existent_field_is_required(Constraint $first)
    {
        $this->required('field', [
            $first
        ]);

        $exception = new NonExistentFieldException('non-existent', ['field']);
        $this->shouldThrow($exception)->duringGet('non-existent');
    }

    public function it_is_able_to_builds_a_collection_constraint()
    {
        $this->required('field');

        $collection = $this->build()[0];

        $collection->shouldBeAnInstanceOf(Collection::class);
        $collection->fields->shouldHaveKey('field');
    }

}
