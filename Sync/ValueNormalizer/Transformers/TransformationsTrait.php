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

use Mautic\IntegrationsBundle\Sync\DAO\Value\NormalizedValueDAO;
use MauticPlugin\MauticVtigerCrmBundle\Exceptions\InvalidObjectValueException;
use MauticPlugin\MauticVtigerCrmBundle\Exceptions\InvalidQueryArgumentException;

/**
 * Trait TransformationsTrait.
 */
trait TransformationsTrait
{
    private $transformations = [
        NormalizedValueDAO::EMAIL_TYPE            => [
            'func' => 'transformEmail',
        ],
        NormalizedValueDAO::STRING_TYPE           => [
            'func' => 'transformString',
        ],
        NormalizedValueDAO::PHONE_TYPE            => [
            'func' => 'transformPhone',
        ],
        NormalizedValueDAO::BOOLEAN_TYPE          => [
            'func' => 'transformBoolean',
        ],
        TransformerInterface::PICKLIST_TYPE       => [
            'func' => 'transformPicklist',
        ],
        TransformerInterface::REFERENCE_TYPE      => [
            'func' => 'transformReference',
        ],
        TransformerInterface::DNC_TYPE            => [
            'func' => 'transformDNC',
        ],
        NormalizedValueDAO::INT_TYPE              => [
            'func' => 'transformInt',
        ],
        NormalizedValueDAO::DATE_TYPE             => [
            'func' => 'transformDate',
        ],
        TransformerInterface::CURRENCY_TYPE       => [
            'func' => 'transformCurrency',
        ],
        NormalizedValueDAO::DOUBLE_TYPE           => [
            'func' => 'transformDouble',
        ],
        NormalizedValueDAO::TEXT_TYPE             => [
            'func' => 'transformString',
        ],
        TransformerInterface::INTEGER_TYPE        => [
            'func' => 'transformInt',
        ],
        TransformerInterface::MULTI_PICKLIST_TYPE => [
            'func' => 'transformMultiPicklist',
        ],
        TransformerInterface::SKYPE_TYPE          => [
            'func' => 'transformSkype',
        ],
        TransformerInterface::TIME_TYPE           => [
            'func' => 'transformTime',
        ],
        TransformerInterface::URL_TYPE            => [
            'func' => 'transformString',
        ],
        TransformerInterface::AUTOGENERATED_TYPE  => [
            'func' => 'transformString',
        ],
        NormalizedValueDAO::DATETIME_TYPE         => [
            'func' => 'transformDatetime',
        ],
    ];

    /**
     * @param          $typeName
     * @param mixed    $value
     *
     * @return NormalizedValueDAO
     * @throws InvalidObjectValueException
     * @throws InvalidQueryArgumentException
     */
    public function transform($typeName, $value): NormalizedValueDAO
    {
        if (!isset($this->transformations[$typeName])) {
            throw new InvalidQueryArgumentException(
                sprintf('Unknown type "%s", cannot transform. Value type: %s', $typeName, var_export($value, true))
            );
        }

        $transformationMethod = $this->transformations[$typeName]['func'];
        $transformedValue     = $this->$transformationMethod($value);
        if (
            is_null($transformedValue)
            && isset($this->transformations['func']['required'])
            && $this->transformations['func']['required']
        ) {
            throw new InvalidObjectValueException('Required property has null value', $transformedValue, $typeName);
        }

        return new NormalizedValueDAO($typeName, $value, $transformedValue);
    }

    /**
     * @param null|string $value
     *
     * @return null|string
     */
    protected function transformEmail(?string $value): ?string
    {
        if (is_null($value) || 0 === strlen(trim($value))) {
            return null;
        }
        $value = $this->transformString($value);

        return $value;
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    protected function transformString($value): ?string
    {
        if (is_null($value)) {
            return $value;
        }

        return (string)$value;
    }

    /**
     * @param $value
     *
     * @return int|null
     */
    protected function transformBoolean($value): ?int
    {
        if (is_null($value)) {
            return $value;
        }

        return intval((bool)$value);
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    protected function transformPhone($value): ?string
    {
        return $this->transformString($value);
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    protected function transformReference($value): ?string
    {
        return $this->transformString($value);
    }

    /**
     * @param \DateTimeInterface|string $value
     *
     * @return null|string
     */
    protected function transformDate($value): ?string
    {
        return ($value instanceof \DateTimeInterface) ? $value->format('Y-m-d') : $this->transformString($value);
    }

    /**
     * @param $value
     *
     * @return int|null
     */
    protected function transformInt($value): ?int
    {
        return intval($value);
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function transformCurrency($value): ?string
    {
        if (is_null($value)) {
            return null;
        }
        
        return number_format(floatval($value), 2);
    }

    /**
     * @param $value
     *
     * @return float
     */
    protected function transformDouble($value): float
    {
        return doubleval($value);
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function transformSkype($value): string
    {
        return $this->transformString((string)$value);
    }

    /**
     * @param \DateTimeInterface|string $time
     *
     * @return null|string
     */
    protected function transformTime($time): string
    {
        return $time instanceof \DateTimeInterface ? $time->format('H:i:s') : $this->transformString($time);
    }

    /**
     * @param \DateTimeInterface|string $time
     *
     * @return null|string
     */
    protected function transformDatetime($time): ?string
    {
        return $time instanceof \DateTimeInterface ? $time->format('Y-m-d H:i:s') : $this->transformString($time);
    }
}
