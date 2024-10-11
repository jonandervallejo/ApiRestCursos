<?php

namespace App\Entity;

use App\Repository\AsignaturaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Curso;

#[ORM\Entity(repositoryClass: AsignaturaRepository::class)]
class Asignatura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['asignatura:read', 'asignatura:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['asignatura:read', 'asignatura:write'])]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['asignatura:read', 'asignatura:write'])]
    private ?int $horas = null;

    #[ORM\ManyToOne(inversedBy: 'asignaturas')]
    #[Groups(['asignatura:read', 'asignatura:write'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?curso $id_curso = null;

    public function __construct(string $nombre, string $horas, ?curso $id_curso)
    {
        $this->nombre = $nombre;
        $this->horas = $horas;
        $this->id_curso = $id_curso;
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

    public function getHoras(): ?int
    {
        return $this->horas;
    }

    public function setHoras(?int $horas): static
    {
        $this->horas = $horas;

        return $this;
    }

    public function getIdCurso(): ?curso
    {
        return $this->id_curso;
    }

    public function setIdCurso(?curso $id_curso): static
    {
        $this->id_curso = $id_curso;

        return $this;
    }
}
