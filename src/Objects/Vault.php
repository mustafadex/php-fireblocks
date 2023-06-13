<?php

namespace Mustafadex\PhpFireblocks\Objects;

/**
 * @property string $id
 * @property string $name
 * @property mixed|Asset[] $assets
 * @property boolean $hiddenOnUI
 * @property string $customerRefId
 * @property boolean $autoFuel
 */
class Vault extends BaseObject
{

    protected function init()
    {
        foreach ($this->assets as &$asset) {
            if (is_object($asset)){
                $asset = self::cast($asset, Asset::class);
            }
        }
    }


}