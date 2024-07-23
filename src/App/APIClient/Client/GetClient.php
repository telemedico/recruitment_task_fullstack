<?php
declare(strict_types=1);

namespace App\NBPApi\Client;
use App\APIClient\Exceptions\RequestBuildException;
use App\Utils\ArrayHelper;

class GetClient extends AbstractClient
{
    protected $method = 'GET';

    /**
     * Build the request url with the specified parameters
     *
     * @return string
     * @throws RequestBuildException
     */
    protected function buildUri(): string
    {
        $baseUri = parent::buildUri();
        if (empty($this->request)) {
            return $baseUri;
        }
        try {
            $data  = $this->request->query->all();
            $query = strpos($baseUri, '?') ? '&' : '?';

            /** @psalm-suppress MixedArgumentTypeCoercion */
            $query .= ArrayHelper::arrayIsList($data)
                ? implode('&', $data)
                : http_build_query($data);
        } catch (\Throwable $t) {
            $this->delegate->getLogger()->error("API Client error: {$t->getMessage()}");
            throw new RequestBuildException('Cannot build GET query string', $t);
        }
        return "{$baseUri}{$query}";
    }
}
