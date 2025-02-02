<?php

namespace Carpenstar\ByBitAPI\Core\Objects;

use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;
use Carpenstar\ByBitAPI\Core\Helpers\DateTimeHelper;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;

abstract class AbstractParameters implements IParametersInterface
{
    protected array $requiredFields = [];

    protected array $requiredBetweenFields = [];

    /**
     * @return array
     */
    public function array(): array
    {
        $entity = $this;
        $entityMethods = get_class_methods($this);
        $params = [];

        array_walk($entityMethods, function ($method) use (&$entity, &$params) {
            if (substr($method, 0, 3) == 'get') {
                $entityProperty = lcfirst(substr($method, 3));
                if (isset($entity->$entityProperty)) {

                    if ($entity->$method() instanceof \DateTime) {
                        $ePropertyVal = DateTimeHelper::makeTimestampFromDateString($entity->$method()->format("Y-m-d H:i:s"));
                    } else {
                        $ePropertyVal = (string)$entity->$method();
                    }

                    $params[$entityProperty] = $ePropertyVal;

                    $propIndex = array_search($entityProperty, $entity->requiredFields, true);

                    if($propIndex > -1) {
                        unset($entity->requiredFields[$propIndex]);
                    }

                    if (!empty($entity->requiredBetweenFields)) {
                        foreach ($entity->requiredBetweenFields as $index => $condition) {
                            if (in_array($entityProperty, $condition)) {
                                unset($entity->requiredBetweenFields[$index]);
                                break;
                            }
                        }
                    }
                }
            }
        });

        return $params;
    }

    protected function setRequiredField(string $fieldName): self
    {
        $this->requiredFields[] = $fieldName;
        return $this;
    }

    protected function setRequiredBetweenField(string $fieldOne, string $fieldTwo): self
    {
        $this->requiredBetweenFields[] = [$fieldOne, $fieldTwo];
        return $this;
    }
}
