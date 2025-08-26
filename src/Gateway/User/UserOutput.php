<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Gateway\User;

use App\Event\Constants\ContactVerificationStatus;
use App\Event\Constants\UserStatus;
use Carbon\Carbon;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserOutput implements JWTUserInterface
{
    public ?string $uuid;

    public ?string $accountUuid;

    public ?int $clientApp;

    public ?ContactOutput $contact;

    #[Assert\NotBlank(message: "User status cannot be empty.")]
    public UserStatus $status = UserStatus::ACTIVE;

    public array $roles = [];

    #[Assert\NotBlank(message: "User username cannot be empty.")]
    public string $username;

    public static function createFromPayload($username, array $payload)
    {
        $user = new self();
        $user->uuid = $payload['uid'] ?? null;
        $user->accountUuid = $payload['cid'] ?? null;
        return $user;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->uuid;
    }
}

class ContactOutput
{
    public ?PhoneOutput $mainPhone = null;

    public ?PhoneOutput $secondaryPhone = null;

    public ?EmailOutput $mainEmail = null;

    public ?EmailOutput $secondaryEmail = null;

    public static function create(
        string      $mainEmail,
        ?string     $secondaryEmail = null,
        ?PhoneInput $mainPhone = null,
        ?PhoneInput $secondaryPhone = null,
    ): self
    {
        $contact = new Contact();
        $contact->mainEmail = EmailOutput::create($mainEmail);
        $contact->secondaryEmail = EmailOutput::create($secondaryEmail);
        $contact->mainPhone = EmailOutput::create($mainPhone);
        $contact->secondaryPhone = EmailOutput::create($secondaryPhone);
        return $contact;
    }
}


class EmailOutput
{
    public string $email;

    public ContactVerificationStatus $verificationStatus = ContactVerificationStatus::NOT_VERIFIED;

    public ?Carbon $verifiedAt = null;
}

class PhoneOutput
{
    public ?int $country = null;

    public ?int $zoneCode = null;

    public ?int $number = null;

    public ?string $rawNumber = null;

    public ContactVerificationStatus $verificationStatus = ContactVerificationStatus::NOT_VERIFIED;

    public ?Carbon $verifiedAt = null;
}

class Role
{
    const ADMIN = 'ADMIN';
    const MANAGER = 'MANAGER';
    const CUSTOMER = 'CUSTOMER';
}
