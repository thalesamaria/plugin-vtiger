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

namespace MauticPlugin\MauticVtigerCrmBundle\Sync\ValueNormalizer\Transformers;

use Mautic\LeadBundle\Entity\DoNotContact;

/**
 * Class VtigerMauticTransformer
 *
 * @package MauticPlugin\MauticVtigerCrmBundle\Sync\ValueNormalizer\Transformers
 */
final class VtigerMauticTransformer implements TransformerInterface
{
    use TransformationsTrait;

    protected function transformDNC($mauticValue)
    {
        return $mauticValue ? DoNotContact::UNSUBSCRIBED : DoNotContact::IS_CONTACTABLE;
    }

    /**
     * @param $mauticValue
     *
     * @return null|string
     */
    protected function transformMultiPicklist($mauticValue)
    {
        return $this->transformString($mauticValue);
    }

    /**
     * @param \DateTimeInterface $value
     *
     * @return null|string
     */
    protected function transformDate($value): ?string
    {
        if (is_null($value) || $value === "" || $value === '0000-00-00') {
            return null;
        }

        $dateObject = \DateTime::createFromFormat('Y-m-d', $value);

        return $dateObject->format('Y-m-d');
    }

}
