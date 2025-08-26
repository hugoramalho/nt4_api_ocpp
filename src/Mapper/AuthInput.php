<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 20/08/2025
 **/

namespace App\Mapper;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class AuthInput implements PasswordAuthenticatedUserInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 10, max: 500)]
        public string $username,

        #[Assert\NotBlank]
        public string $password,

    ) {
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
