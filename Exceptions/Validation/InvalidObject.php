<?php

declare(strict_types=1);

/*
 * @copyright   2018 Mautic Inc. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://www.mautic.com
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticVtigerCrmBundle\Exceptions\Validation;

use MauticPlugin\MauticVtigerCrmBundle\Exceptions\InvalidObjectException;
use MauticPlugin\MauticVtigerCrmBundle\Vtiger\Model\ModuleFieldInfo;

class InvalidObject extends InvalidObjectException
{
    /**
     * @param array           $violations
     * @param ModuleFieldInfo $fieldInfo
     * @param                 $fieldValue
     */
    public function __construct(array $violations, ModuleFieldInfo $fieldInfo, $fieldValue) {
        $violationsMessages = [];
        foreach ($violations as $violation) {
            $violationsMessages[] = $violation->getMessage;
        }

        $message = sprintf("Validation of %s failed. Field value: '%s'. %s",
            $fieldInfo->getName(),
            $fieldValue,
            join('. ', $violationsMessages)
            );

        parent::__construct($message);
    }
}
