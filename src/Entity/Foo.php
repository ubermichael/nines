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
use Nines\MediaBundle\Entity\CitationInterface;
use Nines\MediaBundle\Entity\CitationTrait;
use Nines\MediaBundle\Entity\ContributorInterface;
use Nines\MediaBundle\Entity\ContributorTrait;
use Nines\MediaBundle\Entity\ImageContainerInterface;
use Nines\MediaBundle\Entity\ImageContainerTrait;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=FooRepository::class)
 */
class Foo extends AbstractEntity implements ImageContainerInterface, LinkableInterface, ContributorInterface, CitationInterface {
    use ImageContainerTrait {
        ImageContainerTrait::__construct as image_construct;
    }

    use LinkableTrait {
        LinkableTrait::__construct as link_construct;
    }

    use ContributorTrait {
        ContributorTrait::__construct as contrib_construct;
    }

    use CitationTrait {
        CitationTrait::__construct as citation_construct;
    }

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    public function __construct() {
        parent::__construct();
        $this->image_construct();
        $this->link_construct();
        $this->contrib_construct();
        $this->citation_construct();
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
