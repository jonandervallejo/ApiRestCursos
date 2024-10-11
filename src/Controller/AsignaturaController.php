<?php

namespace App\Controller;

use App\Entity\Asignatura;
use App\Repository\AsignaturaRepository;
use App\Repository\CursoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AsignaturaController extends AbstractController
{
    #[Route('/ws/asignatura', name: 'app_asignatura', methods: ['GET'])]
    public function index(AsignaturaRepository $asignaturaRepository, SerializerInterface $serializerInterface): JsonResponse
    {
       
        return $this ->convertToJson($asignaturaRepository->findAll());
    }


    #[Route('/ws/asignatura/add', name: 'app_asignatura_add', methods: ['POST'])]
    public function addAsignatura(AsignaturaRepository $asignaturaRepository, CursoRepository $cursoRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        //filtrar si hay datos
        if (empty($data['nombre']) || empty($data['horas'])) {
            return new JsonResponse(['status' => 'Faltan datos'], Response::HTTP_BAD_REQUEST);
        }
    
        //encontrar el curso usando el ID proporcionado
        $curso = $cursoRepository->findOneBy(['id'=>$data['id_curso']]);

        if (!$curso) {
            return new JsonResponse(['status' => 'Curso no encontrado'], Response::HTTP_NOT_FOUND);
        }

        //crear objeto asignatura con nombre y descripcion
        $asignatura = new Asignatura($data['nombre'], $data['horas'], $curso);

        //guardar asignatura en la base de datos
        $asignaturaRepository->add($asignatura, true);

        return new JsonResponse(['status' => 'Asignatura creada'], Response::HTTP_ACCEPTED);
    }


    #[Route('/ws/asignatura/delete/{id}', name: 'app_asignatura_delete', methods: ['DELETE'])]
    public function deleteAsignatura(AsignaturaRepository $asignaturaRepository, int $id): JsonResponse
    {
        $asignatura = $asignaturaRepository->findOneBy(['id' => $id]);

        if(empty($asignatura)){
            throw new NotFoundHttpException('No existe la asignatura');
        }

        $asignaturaRepository->delete($asignatura, true);

        return new JsonResponse(['status' => 'Asignatura eliminada'], Response::HTTP_ACCEPTED);
    }


    private function convertToJson($data):JsonResponse
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(null, null, null, null, null, null, [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ])];
        $serializer = new Serializer($normalizers, $encoders);
        $normalized = $serializer->normalize($data,null,array(DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'));
        $jsonContent = $serializer->serialize($normalized, 'json');
        return JsonResponse::fromJsonString($jsonContent, 200);
    }
}
