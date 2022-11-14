<?php

namespace App\Serializer;

use App\Entity\Service;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

class ServiceNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = "ServiceNormalizerAlreadyCalled";

    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof Service;
    }

    /**
     * @param Service $object
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $object->setThumbnailUrl($this->storage->resolveUri($object, 'thumbnailFile'));
       
        $context[self::ALREADY_CALLED] = true;
        
        return $this->normalizer->normalize($object, $format, $context);
    }
}