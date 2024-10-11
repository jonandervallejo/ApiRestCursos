<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use App\Entity\Asignatura;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CursoRepository::class)]
class Curso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    /**
     * @var Collection<int, Asignatura>
     */
    #[ORM\OneToMany(targetEntity: Asignatura::class, mappedBy: 'id_curso', orphanRemoval: true)]
    private Collection $asignaturas;

    public function __construct(string $nombre, string $descripcion)
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection<int, Asignatura>
     */
    public function getAsignaturas(): Collection
    {
        return $this->asignaturas;
    }

    public function addAsignatura(Asignatura $asignatura): static
    {
        if (!$this->asignaturas->contains($asignatura)) {
            $this->asignaturas->add($asignatura);
            $asignatura->setIdCurso($this);
        }

        return $this;
    }

    public function removeAsignatura(Asignatura $asignatura): static
    {
        if ($this->asignaturas->removeElement($asignatura)) {
            // set the owning side to null (unless already changed)
            if ($asignatura->getIdCurso() === $this) {
                $asignatura->setIdCurso(null);
            }
        }

        return $this;
    }
}
