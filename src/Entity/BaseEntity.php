<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 21/08/2025
 **/

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class BaseEntity
{
    const IDP_SCHEMA = 'nt4_identity_provider';
    const FINANCE_SCHEMA = 'nt4_banking';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', nullable: false, options: ['unsigned' => true])]
    public ?int $id;

//    #[ORM\Column(name: 'owner_account', type: 'string', nullable: true, options: ['unsigned' => true])]
//    #[SerializedName('owner_account')]
    public ?string $ownerAccount = null;

    #[ORM\Column(name: 'created_by', type: 'string', nullable: true, options: ['unsigned' => true])]
    #[SerializedName('created_by')]
    public ?string $createdBy = null;

    #[ORM\Column(name: 'updated_by', type: 'string', nullable: true, options: ['unsigned' => true])]
    #[SerializedName('updated_by')]
    public ?string $updatedBy = null;

    #[ORM\Column(name: 'created_at', type: 'carbon', nullable: false)]
    #[SerializedName('created_at')]
    public ?Carbon $createdAt = null;

    #[ORM\Column('updated_at', type: 'carbon', nullable: true)]
    #[SerializedName('updated_at')]
    public ?Carbon $updatedAt = null;

    #[ORM\Column('deleted', type: 'boolean', nullable: false)]
    #[Ignore]
    public bool $deleted = false;

}
