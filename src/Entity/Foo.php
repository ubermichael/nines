<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\FooRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContributorInterface;
use Nines\UtilBundle\Entity\ContributorTrait;
use Nines\UtilBundle\Entity\LinkableInterface;
use Nines\UtilBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\ReferenceableInterface;
use Nines\UtilBundle\Entity\ReferenceableTrait;

/**
 * @ORM\Entity(repositoryClass=FooRepository::class)
 */
class Foo extends AbstractEntity implements LinkableInterface, ContributorInterface, ReferenceableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as link_construct;
    }

    use ContributorTrait {
        ContributorTrait::__construct as contrib_construct;
    }

    use ReferenceableTrait {
        ReferenceableTrait::__construct as reference_construct;
    }

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    public function __construct() {
        parent::__construct();
        $this->link_construct();
        $this->contrib_construct();
        $this->reference_construct();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string {
        return 'Foo#' . $this->id;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(string $name) : self {
        $this->name = $name;

        return $this;
    }
}
