<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\LessonInterface;
use App\Serializer\Processor\FileHrefProcessor;
use App\Serializer\Processor\LessonCompletedProcessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LessonNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $fileHrefProcessor;
    private $lessonCompletedProcessor;

    public function __construct(
        ObjectNormalizer $normalizer,
        FileHrefProcessor $fileHrefProcessor,
        LessonCompletedProcessor $lessonCompletedProcessor
    ) {
        $this->normalizer = $normalizer;
        $this->fileHrefProcessor = $fileHrefProcessor;
        $this->lessonCompletedProcessor = $lessonCompletedProcessor;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->fileHrefProcessor->process($object, $data);
        $data = $this->lessonCompletedProcessor->process($object, $data);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof LessonInterface;
    }
}
