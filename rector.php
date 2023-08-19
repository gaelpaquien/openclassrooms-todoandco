<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\CodingStyle\Rector\Switch_\BinarySwitchToIfElseRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);
    $rectorConfig->parallel();

    $rectorConfig->skip([
        __DIR__ . '/src/Entity/Customer/TimestampableTrait.php',
        __DIR__ . '/src/Entity/Customer/*Translation.php',
        BinarySwitchToIfElseRector::class,
        VarConstantCommentRector::class,
        NewlineAfterStatementRector::class,
        NewlineBeforeNewAssignSetRector::class,
    ]);

    $rectorConfig->sets([
        SetList::PSR_4,
        SetList::PHP_74,
        SetList::PHP_80,
        SetList::PHP_81,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        // SetList::NAMING,
        // SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        DoctrineSetList::DOCTRINE_DBAL_30,
        SymfonySetList::SYMFONY_50_TYPES,
        SymfonySetList::SYMFONY_50,
        SymfonySetList::SYMFONY_52,
        SymfonySetList::SYMFONY_52_VALIDATOR_ATTRIBUTES,
        SymfonySetList::SYMFONY_53,
        SymfonySetList::SYMFONY_54,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);

    $rectorConfig->ruleWithConfiguration(ClassPropertyAssignToConstructorPromotionRector::class, [
        ClassPropertyAssignToConstructorPromotionRector::INLINE_PUBLIC => true,
    ]);
    $rectorConfig->ruleWithConfiguration(TypedPropertyFromAssignsRector::class, [
        TypedPropertyFromAssignsRector::INLINE_PUBLIC => true,
    ]);

    $services = $rectorConfig->services();
    $services->set(FinalizeClassesWithoutChildrenRector::class);
};