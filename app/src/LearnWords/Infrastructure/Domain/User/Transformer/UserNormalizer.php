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

namespace LearnWords\Infrastructure\Domain\User\Transformer;


use LearnWords\Domain\User\User;
use LearnWords\Infrastructure\Domain\User\Dto\UserDto;
use PlanB\Edge\Domain\Dto\Dto;
use PlanB\Edge\Domain\Transformer\Transformer;

final class UserNormalizer extends Transformer
{

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === User::class;
    }

    /**
     * @param UserDto $data
     * @return User
     */
    public function create(Dto $data): User
    {
        return new User($data->username, $data->password);
    }

    /**
     * @param UserDto $data
     * @param User $user
     * @return User
     */
    public function update(Dto $data, $user): User
    {
        return $user->update($data->username);
    }
}
