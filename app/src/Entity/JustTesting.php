<?php

/**
 * Justtesting entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class JustTesting
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\JustTestingRepository")
 * @ORM\Table(name="justtesting")
 */
class JustTesting
{
    /**
     * Primary key.
     *
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
