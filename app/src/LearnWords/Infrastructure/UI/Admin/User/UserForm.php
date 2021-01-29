<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LearnWords\Infrastructure\UI\Admin\User;

use LearnWords\Domain\User\User;
use LearnWords\Infrastructure\Domain\User\Dto\UserDto;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Symfony\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


final class UserForm extends FormConfigurator
{
    static public function attachTo(): string
    {
        return UserAdmin::class;
    }

    public function configure(User $user = null): void
    {
        $this
            ->add('username', TextType::class)
            ->add('password', PasswordType::class);
    }

    public function transform(?object $data)
    {
        if (null === $data) {
            return null;
        }
        return UserDto::fromObject($data);
    }
}
