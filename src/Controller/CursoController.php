<?php

namespace App\Controller;

use App\Entity\Curso;
use App\Repository\CursoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;



class CursoController extends AbstractController
{
    #[Route('/ws/curso', name: 'app_curso', methods: ['GET'])]
    public function index(CursoRepository $cursoRepository): JsonResponse
    {
        return $this ->convertToJson($cursoRepository->findAll());
    }


    #[Route('/ws/curso/add', name: 'app_curso_add', methods: ['POST'])]
    public function addCurso(CursoRepository $cursoRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['nombre']) || empty($data['descripcion'])) {
            throw new NotFoundHttpException('Faltan parámetros');
        }

        $curso = new Curso($data['nombre'], $data['descripcion']);

        $cursoRepository->add($curso, $data);

        return new JsonResponse(['status' => 'Curso creado'], Response::HTTP_ACCEPTED);
    }


    #[Route('/ws/curso/delete/{id}', name: 'app_curso_delete', methods: ['DELETE'])]
    public function deleteCurso(CursoRepository $cursoRepository, int $id): JsonResponse
    {
        $curso = $cursoRepository->findOneBy(['id' => $id]);

        if(empty($curso)){
            throw new NotFoundHttpException('No existe el curso');
        }

        $cursoRepository->delete($curso);

        return new JsonResponse(['status' => 'Curso eliminado'], Response::HTTP_ACCEPTED);
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
