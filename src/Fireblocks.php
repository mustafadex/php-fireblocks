<?php

namespace Mustafadex\PhpFireblocks;

use Mustafadex\PhpFireblocks\Objects\Vault;

class Fireblocks
{

    private Request $request;

    public function __construct($apiKey, $secretKey)
    {
        $this->request = new Request($apiKey, $secretKey);
    }

    /**
     * Gets all vault accounts in your workspace.
     * @param array{namePrefix: string,
     *     nameSuffix: string,
     *     minAmountThreshold: int,
     *     assetId:string,
     *     maxBip44AddressIndexUsed: int,
     *     maxBip44ChangeAddressIndexUsed: int} $params
     * @return mixed|Vault[]
     */
    public function getVaults(array $params = [])
    {
        return $this->request->get('/v1/vault/accounts', ['query' => $params], [], Vault::class);
    }

    /**
     * @param $id
     * @return mixed|Vault
     */
    public function getVaultById($id)
    {
        return $this->request->get('/v1/vault/accounts/'. $id, null, [], Vault::class);
    }

}