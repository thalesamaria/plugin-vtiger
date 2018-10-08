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

namespace MauticPlugin\MauticVtigerCrmBundle\Vtiger\Model;

/**
 * Class ModuleFieldInfo
 *
 * @see
 * public 'name' => string 'salutationtype' (length=14)
 * public 'label' => string 'Salutation' (length=10)
 * public 'mandatory' => boolean false
 * public 'type' =>
 * object(stdClass)[890]
 * ...
 * public 'isunique' => boolean false
 * public 'nullable' => boolean true
 * public 'editable' => boolean true
 * public 'default' => string '' (length=0)
 *
 * @package MauticPlugin\MauticVtigerCrmBundle\Vtiger\Model
 */
class ModuleFieldInfo
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var bool
     */
    private $mandatory;

    /**
     * @var mixed
     */
    private $type;

    /**
     * @var bool
     */
    private $isUnique;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var bool
     */
    private $editable;

    /**
     * @var String
     */
    private $default;

    public function __construct(\stdClass $data)
    {
        $this->label = $data->label;
        $this->name = $data->name;
        $this->mandatory = $data->mandatory;
        $this->type = $data->type;
        if (!isset($data->isunique) && in_array('autogenerated', (array) $data->type)) {
            $this->isUnique = true;
        }else if (!isset($data->isunique)) {
            $this->isUnique = false;
        } else {
            $this->isUnique = $data->isunique;
        }
        $this->nullable = $data->nullable;
        $this->editable = $data->editable;

        $this->default = isset($data->default) ? $data->default : null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isMandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->getTypeObject()->name;
    }

    /**
     * @return \stdClass
     */
    public function getTypeObject() {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->isUnique;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->editable;
    }

    /**
     * @return String
     */
    public function getDefault(): String
    {
        return $this->default;
    }
}
