<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CommandDTO;
use App\Form\CommandForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly HubInterface $hub,
    ) {
    }

    /**
     * @throws \JsonException
     */
    #[Route('/start', name: 'start', methods: ['POST'])]
    public function start(Request $request): JsonResponse
    {
        $command = new CommandDTO();

        $form = $this->createForm(CommandForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->hub->publish(new Update(
                $command->syncId,
                json_encode([
                    'action' => 'start',
                    'times' => [
                        'preparation' => $command->preparationTime ?? 10,
                        'shot' => $command->shotTime ?? 120,
                        'warning' => $command->warningTime ?? 30,
                    ]
                ], JSON_THROW_ON_ERROR)
            ));

            return $this->json([
                'message' => 'Message published'
            ]);
        }

        return $this->json([
            'message' => 'Message not published',
            'data' => $form->getErrors(),
        ]);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/stop', name: 'stop', methods: ['POST'])]
    public function stop(Request $request): JsonResponse
    {
        $command = new CommandDTO();

        $form = $this->createForm(CommandForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->hub->publish(new Update(
                $command->syncId,
                json_encode(['action' => 'stop'], JSON_THROW_ON_ERROR),
            ));

            return $this->json([
                'message' => 'Message published'
            ]);
        }

        return $this->json([
            'message' => 'Message not published'
        ]);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/alert', name: 'alert', methods: ['POST'])]
    public function alert(Request $request): JsonResponse
    {
        $command = new CommandDTO();

        $form = $this->createForm(CommandForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->hub->publish(new Update(
                $command->syncId,
                json_encode(['action' => 'alert'], JSON_THROW_ON_ERROR),
            ));

            return $this->json([
                'message' => 'Message published'
            ]);
        }

        return $this->json([
            'message' => 'Message not published'
        ]);
    }
}
