<?php

namespace App\Serializer;

use App\Entity\ProfessionalImage;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

class ProfessionalImageNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = "ProfessionalImageNormalizerAlreadyCalled";

    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof ProfessionalImage;
    }

    /**
     * @param ProfessionalImage $object
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $object->setImageUrl($this->storage->resolveUri($object, 'imageFile'));
       
        $context[self::ALREADY_CALLED] = true;
        
        return $this->normalizer->normalize($object, $format, $context);
    }
}