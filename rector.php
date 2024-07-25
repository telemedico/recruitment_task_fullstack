<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withCache('/tmp/rector', FileCacheStorage::class)
    ->withPaths([
        __DIR__.'/src',
    ])
    ->withImportNames(true, true, false)
    ->withSets(
        [
            SetList::CODING_STYLE,
            SetList::CODE_QUALITY,
            SetList::PRIVATIZATION,
            SetList::TYPE_DECLARATION,
            SetList::EARLY_RETURN,
            SetList::INSTANCEOF,

            SymfonySetList::SYMFONY_CODE_QUALITY,

            DoctrineSetList::DOCTRINE_CODE_QUALITY,
        ]
    )
    ->withRules([
    ])
    ->withSkip([
        SeparateMultiUseImportsRector::class,
        CountArrayToEmptyArrayComparisonRector::class,
        SymplifyQuoteEscapeRector::class,
    ]);
