<?php

namespace Oro\Bundle\ApiBundle\Provider;

use Doctrine\Common\Cache\Cache;
use Oro\Bundle\EntityBundle\ORM\EntityAliasResolver as BaseEntityAliasResolver;
use Psr\Log\LoggerInterface;

/**
 * Provides functionality to get singular and plural aliases for an entity class
 * and resolve entity class by any of these aliases taking into account overridden entities.
 */
class EntityAliasResolver extends BaseEntityAliasResolver
{
    /** @var EntityOverrideProviderInterface */
    private $entityOverrideProvider;

    /**
     * @param EntityAliasLoader               $loader
     * @param EntityOverrideProviderInterface $entityOverrideProvider
     * @param Cache                           $cache
     * @param LoggerInterface                 $logger
     * @param bool                            $debug
     */
    public function __construct(
        EntityAliasLoader $loader,
        EntityOverrideProviderInterface $entityOverrideProvider,
        Cache $cache,
        LoggerInterface $logger,
        $debug
    ) {
        parent::__construct($loader, $cache, $logger, $debug);
        $this->entityOverrideProvider = $entityOverrideProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAlias($entityClass)
    {
        return parent::hasAlias($this->resolveEntityClass($entityClass));
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias($entityClass)
    {
        return parent::getAlias($this->resolveEntityClass($entityClass));
    }

    /**
     * {@inheritdoc}
     */
    public function getPluralAlias($entityClass)
    {
        return parent::getPluralAlias($this->resolveEntityClass($entityClass));
    }

    /**
     * @param string $entityClass
     *
     * @return string
     */
    private function resolveEntityClass($entityClass)
    {
        $substituteEntityClass = $this->entityOverrideProvider->getSubstituteEntityClass($entityClass);
        if ($substituteEntityClass) {
            return $substituteEntityClass;
        }

        return $entityClass;
    }
}
